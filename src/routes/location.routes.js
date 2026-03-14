const express = require('express');
const {
  createLocation,
  getLocations,
  updateLocation,
  deleteLocation,
} = require('../controllers/location.controller');
const protect = require('../middlewares/auth.middleware');
const authorize = require('../middlewares/role.middleware');

const router = express.Router();

router.use(protect, authorize('SUPER_ADMIN'));

router.route('/').post(createLocation).get(getLocations);
router.route('/:id').put(updateLocation).delete(deleteLocation);

module.exports = router;
