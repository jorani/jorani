const SwaggerUI = require('swagger-ui')

import css from 'swagger-ui/dist/swagger-ui.css';

// Parse the config passed by the server
const configElement = document.getElementById('configTag')
const config = JSON.parse(configElement.innerHTML)

const ui = SwaggerUI({
  url: config.baseURL,
  dom_id: '#swagger-ui',
  presets: [
    SwaggerUI.presets.apis
  ]
})
