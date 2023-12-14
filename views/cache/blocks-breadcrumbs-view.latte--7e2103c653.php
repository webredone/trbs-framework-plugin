<?php

use Latte\Runtime as LR;

/** source: /Users/nikolaivanov/Local Sites/trbs/app/public/wp-content/plugins/trbs-framework/src/blocks/breadcrumbs/view.latte */
final class Template7e2103c653 extends Latte\Runtime\Template
{

	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '


';
		if (!empty($breadcrumbs)) /* line 6 */ {
			echo '<ol
  class="breadcrumbs ';
			echo LR\Filters::escapeHtmlAttr($is_dark_cls) /* line 7 */;
			echo '"
  itemscope itemtype="https://schema.org/BreadcrumbList"
>
';
			foreach ($iterator = $ʟ_it = new Latte\Essential\CachingIterator($breadcrumbs, $ʟ_it ?? null) as $breadcrumb) /* line 10 */ {
				echo '  <li 
    itemprop="itemListElement" 
    itemscope
    itemtype="https://schema.org/ListItem"
  >
    <a 
      itemprop="item" 
      href="';
				echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($breadcrumb['link']['url'])) /* line 18 */;
				echo '" 
    >
      <span itemprop="name">
        ';
				echo $breadcrumb['link']['title'] /* line 21 */;
				echo '
      </span>
    </a>
    <meta itemprop="position" content="';
				echo LR\Filters::escapeHtmlAttr($iterator->counter) /* line 24 */;
				echo '" />
  </li>

';
				if (!$iterator->last) /* line 28 */ {
					echo '  <li
    class="separator"
  >/</li>
';
				}

			}
			$iterator = $ʟ_it = $ʟ_it->getParent();

			echo '</ol>';
		}
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['breadcrumb' => '10'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		$is_dark_cls = !empty($inspector_bg_is_dark) && !empty($inspector_bg_is_dark['checked']) && $inspector_bg_is_dark['checked'] ? ' dark' : '' /* line 1 */;
		return get_defined_vars();
	}
}
