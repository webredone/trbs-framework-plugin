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
			$is_dark_cls = $inspector_bg_is_dark['checked'] ? ' dark' : '' /* line 2 */;
			echo "\n";
			if ($link['url']) /* line 4 */ {
				echo '		<a ';
				echo tr_a($link, 'wp-figure-link', true) /* line 5 */;
				echo '>
';
			}
			echo '
		<figure class="wp-figure wp-figure-test ';
			echo LR\Filters::escapeHtmlAttr($is_dark_cls) /* line 8 */;
			echo '">
			';
			echo LR\Filters::escapeHtmlText(tr_get_media($image, true)) /* line 9 */;
			echo "\n";
			if (strlen($caption['text'])) /* line 10 */ {
				echo '				<figcaption class="wp-figcaption">
					';
				echo LR\Filters::escapeHtmlText($caption['text']) /* line 12 */;
				echo '
				</figcaption>
';
			}
			echo '		</figure>

';
			if ($link['url']) /* line 17 */ {
				echo '		</a>
';
			}
			echo "\n";
		}
	}
}
