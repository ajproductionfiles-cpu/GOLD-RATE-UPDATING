const express = require('express');
const {
  createGoldRate,
  getGoldRates,
  getGoldRateById,
  updateGoldRate,
  deleteGoldRate,
} = require('../controllers/gold-rate.controller');
const protect = require('../middlewares/auth.middleware');
const authorize = require('../middlewares/role.middleware');

const router = express.Router();

router.use(protect);

router.route('/').post(authorize('SUPER_ADMIN'), createGoldRate).get(getGoldRates);
router
  .route('/:id')
  .get(getGoldRateById)
  .put(authorize('SUPER_ADMIN'), updateGoldRate)
  .delete(authorize('SUPER_ADMIN'), deleteGoldRate);

module.exports = router;
