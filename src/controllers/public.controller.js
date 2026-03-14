const { GoldRate, Location } = require('../models');
const asyncHandler = require('../utils/async-handler');
const ApiError = require('../utils/api-error');

const getRateByDisplayCode = asyncHandler(async (req, res) => {
  const { displayCode } = req.params;

  const location = await Location.findOne({ where: { displayCode, isActive: true } });
  if (!location) {
    throw new ApiError(404, 'Display code not found or inactive');
  }

  const latestRate = await GoldRate.findOne({
    where: { locationId: location.id },
    order: [['effectiveDate', 'DESC'], ['createdAt', 'DESC']],
  });

  if (!latestRate) {
    throw new ApiError(404, 'No gold rate found for this location');
  }

  res.json({
    success: true,
    data: {
      location: {
        id: location.id,
        name: location.name,
        displayCode: location.displayCode,
      },
      rates: {
        rate24k: latestRate.rate24k,
        rate22k: latestRate.rate22k,
        rate18k: latestRate.rate18k,
      },
      effectiveDate: latestRate.effectiveDate,
      updatedAt: latestRate.updatedAt,
    },
  });
});

module.exports = {
  getRateByDisplayCode,
};
