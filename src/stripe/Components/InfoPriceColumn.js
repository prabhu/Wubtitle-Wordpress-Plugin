/**
 * Use the CSS tab above to style your Element's container.
 */
import React from 'react';
// import { __ } from '@wordpress/i18n';

const InfoPriceColumn = () => {
	return (
		<div className="column price-column">
			<div className="price">
				<p>Subscribe to professional plan</p>
				<p>
					Price<span>19</span>
				</p>
				<p>
					Vat<span>111</span>
				</p>
				<p>
					Total<span>199</span>per month
				</p>
			</div>
			<div className="disclaimer">
				<p>lorem lorevareg sdgv ergvre grgergerge gergreg wefrgwergw</p>
				<p>
					<a
						href="https://google.it"
						rel="noreferrer"
						target="_blank"
					>
						terms and cond
					</a>
					|
					<a
						href="https://google.it"
						target="_blank"
						rel="noreferrer"
					>
						Privacy
					</a>
				</p>
			</div>
		</div>
	);
};

export default InfoPriceColumn;
