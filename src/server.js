const app = require('./app');
const env = require('./config/env');
const { sequelize, User } = require('./models');

const bootstrap = async () => {
  try {
    await sequelize.authenticate();
    await sequelize.sync();

    const superAdminEmail = 'superadmin@goldrate.com';
    const existingSuperAdmin = await User.findOne({ where: { email: superAdminEmail } });

    if (!existingSuperAdmin) {
      await User.create({
        name: 'Super Admin',
        email: superAdminEmail,
        password: 'Admin@123',
        role: 'SUPER_ADMIN',
      });
      console.log('Default super admin created: superadmin@goldrate.com / Admin@123');
    }

    app.listen(env.port, () => {
      console.log(`Server started on port ${env.port}`);
    });
  } catch (error) {
    console.error('Unable to bootstrap application:', error);
    process.exit(1);
  }
};

bootstrap();
