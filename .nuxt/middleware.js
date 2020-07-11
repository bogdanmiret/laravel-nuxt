const middleware = {}

middleware['global'] = require('..\\resources\\client\\middleware\\global.js')
middleware['global'] = middleware['global'].default || middleware['global']

middleware['guest'] = require('..\\resources\\client\\middleware\\guest.js')
middleware['guest'] = middleware['guest'].default || middleware['guest']

export default middleware
