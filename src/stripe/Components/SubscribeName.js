import React from 'react';
import { __ } from '@wordpress/i18n';

const SubscribeName = (props) => {
	const { name } = props;

	return (
		<p className="price-name">
			{__('Subscribe to', 'wubtitle')} {name} {__('plan', 'wubtitle')}
		</p>
	);
};

export default SubscribeName;
