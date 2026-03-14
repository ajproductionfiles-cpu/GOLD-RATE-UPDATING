const { DataTypes } = require('sequelize');

module.exports = (sequelize) => sequelize.define('Subscription', {
  id: {
    type: DataTypes.INTEGER,
    autoIncrement: true,
    primaryKey: true,
  },
  razorpaySubscriptionId: {
    type: DataTypes.STRING,
    allowNull: false,
    unique: true,
  },
  razorpayCustomerId: {
    type: DataTypes.STRING,
    allowNull: true,
  },
  status: {
    type: DataTypes.STRING(30),
    allowNull: false,
    defaultValue: 'created',
  },
  currentStart: {
    type: DataTypes.DATE,
    allowNull: true,
  },
  currentEnd: {
    type: DataTypes.DATE,
    allowNull: true,
  },
}, {
  tableName: 'subscriptions',
});
