const Razorpay = require('razorpay');
const env = require('../config/env');
const ApiError = require('../utils/api-error');

const razorpayClient = new Razorpay({
  key_id: env.razorpay.keyId,
  key_secret: env.razorpay.keySecret,
});

const createSubscription = async ({ totalCount = 12, customerNotify = 1 }) => {
  if (!env.razorpay.planId) {
    throw new ApiError(500, 'Missing Razorpay plan id configuration');
  }

  return razorpayClient.subscriptions.create({
    plan_id: env.razorpay.planId,
    total_count: totalCount,
    customer_notify: customerNotify,
  });
};

module.exports = {
  createSubscription,
};
