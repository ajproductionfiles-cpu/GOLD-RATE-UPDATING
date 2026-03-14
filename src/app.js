const express = require('express');
const cors = require('cors');
const routes = require('./routes');
const errorMiddleware = require('./middlewares/error.middleware');

const app = express();

app.use(cors());
app.use(express.json());

app.get('/health', (req, res) => {
  res.json({ success: true, message: 'Gold rate API is running' });
});

app.use('/api/v1', routes);

app.use(errorMiddleware);

module.exports = app;
