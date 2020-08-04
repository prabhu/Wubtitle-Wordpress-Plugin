import { __ } from '@wordpress/i18n';

const langExten = {
	it: __('Italian', 'wubtitle'),
	en: __('English', 'wubtitle'),
	es: __('Spanish', 'wubtitle'),
	de: __('German', 'wubtitle'),
	zh: __('Chinese', 'wubtitle'),
	fr: __('French', 'wubtitle'),
};

const statusExten = {
	pending: __('Generating', 'wubtitle'),
	draft: __('Draft', 'wubtitle'),
	enabled: __('Enabled', 'wubtitle'),
	notfound: __('None', 'wubtitle'),
};

const selectOptions = [
	{
		value: 'it',
		label: __('Italian', 'wubtitle'),
	},
	{
		value: 'en',
		label: __('English', 'wubtitle'),
	},
	{
		value: 'es',
		label: __('Spanish', 'wubtitle'),
	},
	{
		value: 'de',
		label: __('German', 'wubtitle'),
	},
	{
		value: 'zh',
		label: __('Chinese', 'wubtitle'),
	},
	{
		value: 'fr',
		label: __('French', 'wubtitle'),
	},
];

const selectOptionsFreePlan = [
	{
		value: 'it',
		label: __('Italian', 'wubtitle'),
		disabled: false,
	},
	{
		value: 'en',
		label: __('English', 'wubtitle'),
		disabled: false,
	},
	{
		value: 'es',
		label: __('Spanish (Pro only)', 'wubtitle'),
		disabled: true,
	},
	{
		value: 'de',
		label: __('German (Pro only)', 'wubtitle'),
		disabled: true,
	},
	{
		value: 'zh',
		label: __('Chinese (Pro only)', 'wubtitle'),
		disabled: true,
	},
	{
		value: 'fr',
		label: __('French (Pro only)', 'wubtitle'),
		disabled: true,
	},
];

export { langExten, statusExten, selectOptions, selectOptionsFreePlan };
