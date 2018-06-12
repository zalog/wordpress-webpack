import webfontloader from 'webfontloader';

// log development env
if ( process.env.NODE_ENV === "development" )
  console.warn(`${process.env.NODE_ENV} mode enabled!`);



/**
 * font loader
 */
webfontloader.load({
  google: {
    families: ['Roboto']
  }
});

/**
 * lazy load app
 */
import('./app/app' /* webpackChunkName: "app" */);
