/**
 * @todo: handle globals in a better way
 */
export const get = ( key, defaultValue ) => window[ key ] || defaultValue;
export const google = () => get( 'google' );
export const wpApi = wp.api;
export const wpApiRequest = wp.apiRequest;
export const wpData = wp.data;
export const wpHooks = wp.hooks;

// Localized Config
export const config = () => get( 'tribe_editor_config', {} );

// Common
export const common = () => config().common || {};
export const adminUrl = () => common().adminUrl || '';
export const rest = () => common().rest || {};
export const restNonce = () => rest().nonce || {};
export const dateSettings = () => common().dateSettings || {};
export const editorConstants = () => common().constants || {};
export const list = () => ( {
	countries: common().countries || {},
	us_states: common().usStates || {},
} );

// TEC
export const tec = () => config().events || {};
export const editor = () => tec().editor || {};
export const settings = () => tec().settings || {};
export const mapsAPI = () => tec().googleMap || {};
export const priceSettings = () => tec().priceSettings || {};
export const tecDateSettings = () => tec().dateSettings || {};
export const timezoneHtml = () => tec().timezoneHTML || '';
export const defaultTimes = () => tec().defaultTimes || {};
export const timezone = () => tec().timeZone || {};

// PRO
export const pro = () => config().eventsPRO || {};
export const editorDefaults = () => pro().defaults || {};

// Tickets
export const tickets = () => config().tickets || {};

// Post Objects
export const postObjects = () => config().post_objects || {};

// Blocks
export const blocks = () => config().blocks || {};
