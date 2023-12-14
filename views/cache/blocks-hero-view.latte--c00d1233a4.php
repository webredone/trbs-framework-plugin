<?php

use Latte\Runtime as LR;

/** source: /Users/nikolaivanov/Local Sites/trbs/app/public/wp-content/plugins/trbs-framework/src/blocks/hero/view.latte */
final class Templatec00d1233a4 extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo "\n";
		if ($title['text']) /* line 5 */ {
			echo '<section 
  class="hero-main ';
			echo LR\Filters::escapeHtmlAttr($is_dark_cls) /* line 4 */;
			echo '"
>
  <div class="hero-main__cont">
    ';
			echo LR\Filters::escapeHtmlText(tr_get_media('logo.svg', true)) /* line 8 */;
			echo '
    ';
			echo LR\Filters::escapeHtmlText(tr_get_media('logo.svg')) /* line 9 */;
			echo '
    ';
			echo LR\Filters::escapeHtmlText(tr_get_media('testimg.jpg')) /* line 10 */;
			echo '
    ';
			echo LR\Filters::escapeHtmlText(tr_get_media('testimg.jpg', true)) /* line 11 */;
			echo '

';
			ob_start(fn() => '');
			try {
				echo '    <h1>';
				ob_start();
				try {
					echo LR\Filters::escapeHtmlText($title['text']) /* line 13 */;

				} finally {
					$ʟ_ifc[0] = rtrim(ob_get_flush()) === '';
				}
				echo '</h1>
';

			} finally {
				if ($ʟ_ifc[0] ?? null) {
					ob_end_clean();
				} else {
					echo ob_get_clean();
				}
			}
			ob_start(fn() => '');
			try {
				echo '    <p>';
				ob_start();
				try {
					echo LR\Filters::escapeHtmlText($text['text']) /* line 14 */;

				} finally {
					$ʟ_ifc[1] = rtrim(ob_get_flush()) === '';
				}
				echo '</p>
';

			} finally {
				if ($ʟ_ifc[1] ?? null) {
					ob_end_clean();
				} else {
					echo ob_get_clean();
				}
			}
			echo "\n";
			dump($title) /* line 16 */;
			echo '

    ';
			echo LR\Filters::escapeHtmlText(tr_a($cta, 'btn btn--brand')) /* line 19 */;
			echo '

';
			$this->createTemplate(tr_part('test'), $this->params, 'include')->renderToContentType('html') /* line 21 */;
			echo '  </div>
</section>
';
		}
		echo "\n";
	}


	public function prepare(): array
	{
		extract($this->params);

		$is_dark_cls = !empty($inspector_bg_is_dark) && !empty($inspector_bg_is_dark['checked']) && $inspector_bg_is_dark['checked'] ? ' dark' : '' /* line 1 */;
		return get_defined_vars();
	}
}
