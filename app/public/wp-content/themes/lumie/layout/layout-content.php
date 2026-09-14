<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

$content = $content ?? [];
$block = $block ?? [];

if (empty($content)) return;
?>
<div class="content-layout">
	<?php
	foreach ($content as $key => $value) :
		switch ($value['acf_fc_layout']):
			case 'title':
				layout("title", [
					'title' => $value['block_title'],
					'block' => $block,
				]);
				break;

			case 'content':
				$content = new FlexContent();

				$content->setContent($value['content']);

				echo $content->getContent();
				break;

			case 'fold_content':
				$content = new FlexContent();

				$content->setFoldContent($value['content']);

				echo $content->getContent();
				break;

			case 'quote':
				$content = new FlexContent();

				$content->setQuote($value['quote'], $value['author'] ?? '');

				echo $content->getContent();
				break;

			case 'image':
				$content = new FlexContent();

				$content->setImage($value['image']);

				echo $content->getContent();
				break;

			case 'logos':
				$content = new FlexContent();

				$content->setLogos($value['logos'], true);

				echo $content->getContent();
				break;

			case 'accordions':
				$content = new FlexContent();

				$content->setAccordions($value['accordions']);

				echo $content->getContent();
				break;

			case 'specifications':
				$content = new FlexContent();

				$content->setSpecifications($value['specifications'], $value['title'] ?? '');

				echo $content->getContent();
				break;

			case 'form':
				$content = new FlexContent();

				$content->setForm($value['form_id']);

				echo $content->getContent();
				break;

			case 'person':
				$content = new FlexContent();

				$content->setPerson($value['person']);

				echo $content->getContent();
				break;

			case 'buttons_clone':
				$content = new FlexContent();

				$content->setButtons($value['buttons_group']);

				echo $content->getContent();
				break;
		endswitch;
	endforeach;
	?>
</div>
