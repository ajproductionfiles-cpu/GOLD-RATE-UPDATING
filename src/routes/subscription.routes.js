const express = require('express');
const {
  createRazorpaySubscription,
  markSubscriptionActive,
} = require('../controllers/subscription.controller');
const protect = require('../middlewares/auth.middleware');

const router = express.Router();

router.post('/create', protect, createRazorpaySubscription);
router.post('/activate', protect, markSubscriptionActive);

module.exports = router;
