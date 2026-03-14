const express = require('express');
const { getRateByDisplayCode } = require('../controllers/public.controller');

const router = express.Router();

router.get('/rate/:displayCode', getRateByDisplayCode);

module.exports = router;
