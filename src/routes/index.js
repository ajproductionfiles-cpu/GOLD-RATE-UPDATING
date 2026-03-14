const express = require('express');
const authRoutes = require('./auth.routes');
const goldRateRoutes = require('./gold-rate.routes');
const locationRoutes = require('./location.routes');
const publicRoutes = require('./public.routes');
const subscriptionRoutes = require('./subscription.routes');

const router = express.Router();

router.use('/auth', authRoutes);
router.use('/locations', locationRoutes);
router.use('/gold-rates', goldRateRoutes);
router.use('/public', publicRoutes);
router.use('/subscriptions', subscriptionRoutes);

module.exports = router;
