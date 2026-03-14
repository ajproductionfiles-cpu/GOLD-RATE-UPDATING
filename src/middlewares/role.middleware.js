const ApiError = require('../utils/api-error');

const authorize = (...roles) => (req, res, next) => {
  if (!req.user || !roles.includes(req.user.role)) {
    return next(new ApiError(403, 'Forbidden: insufficient permissions'));
  }

  return next();
};

module.exports = authorize;
