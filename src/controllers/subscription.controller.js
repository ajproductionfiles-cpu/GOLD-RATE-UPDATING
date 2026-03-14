const { Subscription, User } = require('../models');
const asyncHandler = require('../utils/async-handler');
const { createSubscription } = require('../services/razorpay.service');
const ApiError = require('../utils/api-error');

const createRazorpaySubscription = asyncHandler(async (req, res) => {
  const { totalCount } = req.body;

  const razorpaySubscription = await createSubscription({ totalCount: totalCount || 12 });

  const subscription = await Subscription.create({
    userId: req.user.id,
    razorpaySubscriptionId: razorpaySubscription.id,
    status: razorpaySubscription.status,
  });

  res.status(201).json({
    success: true,
    data: {
      subscription,
      razorpayKeyId: process.env.RAZORPAY_KEY_ID,
    },
  });
});

const markSubscriptionActive = asyncHandler(async (req, res) => {
  const { razorpaySubscriptionId, currentStart, currentEnd } = req.body;

  const subscription = await Subscription.findOne({ where: { razorpaySubscriptionId } });
  if (!subscription) {
    throw new ApiError(404, 'Subscription not found');
  }

  await subscription.update({
    status: 'active',
    currentStart,
    currentEnd,
  });

  await User.update({ isSubscribed: true }, { where: { id: subscription.userId } });

  res.json({ success: true, message: 'Subscription activated successfully' });
});

module.exports = {
  createRazorpaySubscription,
  markSubscriptionActive,
};
