const { DataTypes } = require('sequelize');

module.exports = (sequelize) => sequelize.define('GoldRate', {
  id: {
    type: DataTypes.INTEGER,
    autoIncrement: true,
    primaryKey: true,
  },
  rate24k: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: false,
  },
  rate22k: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: false,
  },
  rate18k: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: false,
  },
  effectiveDate: {
    type: DataTypes.DATEONLY,
    allowNull: false,
  },
}, {
  tableName: 'gold_rates',
  indexes: [
    {
      unique: true,
      fields: ['location_id', 'effective_date'],
    },
  ],
});
