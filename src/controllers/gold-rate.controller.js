const { GoldRate, Location } = require('../models');
const asyncHandler = require('../utils/async-handler');
const ApiError = require('../utils/api-error');

const createGoldRate = asyncHandler(async (req, res) => {
  const { locationId, rate24k, rate22k, rate18k, effectiveDate } = req.body;

  const location = await Location.findByPk(locationId);
  if (!location) {
    throw new ApiError(404, 'Location not found');
  }

  const rate = await GoldRate.create({ locationId, rate24k, rate22k, rate18k, effectiveDate });
  res.status(201).json({ success: true, data: rate });
});

const getGoldRates = asyncHandler(async (req, res) => {
  const where = {};
  if (req.query.locationId) {
    where.locationId = req.query.locationId;
  }

  const rates = await GoldRate.findAll({
    where,
    include: [{ model: Location, as: 'location', attributes: ['id', 'name', 'displayCode'] }],
    order: [['effectiveDate', 'DESC']],
  });

  res.json({ success: true, data: rates });
});

const getGoldRateById = asyncHandler(async (req, res) => {
  const rate = await GoldRate.findByPk(req.params.id, {
    include: [{ model: Location, as: 'location', attributes: ['id', 'name', 'displayCode'] }],
  });

  if (!rate) {
    throw new ApiError(404, 'Gold rate not found');
  }

  res.json({ success: true, data: rate });
});

const updateGoldRate = asyncHandler(async (req, res) => {
  const rate = await GoldRate.findByPk(req.params.id);
  if (!rate) {
    throw new ApiError(404, 'Gold rate not found');
  }

  const { rate24k, rate22k, rate18k, effectiveDate } = req.body;
  await rate.update({ rate24k, rate22k, rate18k, effectiveDate });

  res.json({ success: true, data: rate });
});

const deleteGoldRate = asyncHandler(async (req, res) => {
  const rate = await GoldRate.findByPk(req.params.id);
  if (!rate) {
    throw new ApiError(404, 'Gold rate not found');
  }

  await rate.destroy();
  res.json({ success: true, message: 'Gold rate deleted successfully' });
});

module.exports = {
  createGoldRate,
  getGoldRates,
  getGoldRateById,
  updateGoldRate,
  deleteGoldRate,
};
