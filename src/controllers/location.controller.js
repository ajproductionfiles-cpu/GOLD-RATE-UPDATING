const { Location } = require('../models');
const asyncHandler = require('../utils/async-handler');
const ApiError = require('../utils/api-error');

const createLocation = asyncHandler(async (req, res) => {
  const { name, displayCode, isActive } = req.body;

  const location = await Location.create({ name, displayCode, isActive });
  res.status(201).json({ success: true, data: location });
});

const getLocations = asyncHandler(async (req, res) => {
  const locations = await Location.findAll({ order: [['id', 'DESC']] });
  res.json({ success: true, data: locations });
});

const updateLocation = asyncHandler(async (req, res) => {
  const location = await Location.findByPk(req.params.id);
  if (!location) {
    throw new ApiError(404, 'Location not found');
  }

  const { name, displayCode, isActive } = req.body;
  await location.update({ name, displayCode, isActive });

  res.json({ success: true, data: location });
});

const deleteLocation = asyncHandler(async (req, res) => {
  const location = await Location.findByPk(req.params.id);
  if (!location) {
    throw new ApiError(404, 'Location not found');
  }

  await location.destroy();
  res.json({ success: true, message: 'Location deleted successfully' });
});

module.exports = {
  createLocation,
  getLocations,
  updateLocation,
  deleteLocation,
};
