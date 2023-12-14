<?php

use Latte\Runtime as LR;

/** source: /Users/nikolaivanov/Local Sites/trbs/app/public/wp-content/plugins/trbs-framework/src/blocks/image/view.latte */
final class Template6c2ce1d54c extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		if (!empty($image['src'])) /* line 1 */ {
			$is_dark_cls = !empty($inspector_bg_is_dark) && !empty($inspector_bg_is_dark['checked']) && $inspector_bg_is_dark['checked'] ? ' dark' : '' /* line 2 */;
			echo '

';
			if ($link['url']) /* line 5 */ {
				echo '		<a ';
				echo tr_a($link, 'wp-figure-link', true) /* line 6 */;
				echo '>
';
			}
			echo '
		<figure class="wp-figure wp-figure-test ';
			echo LR\Filters::escapeHtmlAttr($is_dark_cls) /* line 9 */;
			echo '">
			';
			echo LR\Filters::escapeHtmlText(tr_get_media($image, true)) /* line 10 */;
			echo "\n";
			if (strlen($caption['text'])) /* line 11 */ {
				echo '				<figcaption class="wp-figcaption">
					';
				echo LR\Filters::escapeHtmlText($caption['text']) /* line 13 */;
				echo '
				</figcaption>
';
			}
			echo '		</figure>

';
			if ($link['url']) /* line 18 */ {
				echo '		</a>
';
			}
			echo "\n";
		}
		echo "\n";
	}
}
