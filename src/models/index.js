const sequelize = require('../config/database');

const User = require('./user.model')(sequelize);
const Location = require('./location.model')(sequelize);
const GoldRate = require('./gold-rate.model')(sequelize);
const Subscription = require('./subscription.model')(sequelize);

Location.hasMany(GoldRate, { foreignKey: 'locationId', as: 'goldRates', onDelete: 'CASCADE' });
GoldRate.belongsTo(Location, { foreignKey: 'locationId', as: 'location' });

User.hasMany(Subscription, { foreignKey: 'userId', as: 'subscriptions', onDelete: 'CASCADE' });
Subscription.belongsTo(User, { foreignKey: 'userId', as: 'user' });

module.exports = {
  sequelize,
  User,
  Location,
  GoldRate,
  Subscription,
};
