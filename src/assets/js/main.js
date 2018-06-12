// log development env
if ( process.env.NODE_ENV === "development" )
  console.warn(`${process.env.NODE_ENV} mode enabled!`);



/**
 * lazy load app
 */
import('./app/app' /* webpackChunkName: "app" */);
