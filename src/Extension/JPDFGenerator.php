<?php namespace Joomla\Plugin\System\JPDFGenerator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Libraries\JMpdf\JMpdf;

/**
 * Jpdfgenerator plugin.
 *
 * @package  pdfrealty
 * @since    1.0
 */
class JPDFGenerator extends CMSPlugin
{

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;


	public function onAjaxJpdfgenerator()
	{
		$app                   = Factory::getApplication();
		$request_data          = $app->input->getArray();
		$html                  = '';
		$config                = [];
		$action                = 'stream';
		$template_name         = 'default';
		$template_path_default = JPATH_ROOT . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, ['plugins', 'system', 'jpdfgenerator', 'tmpl']);
		$template_path_theme   = JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $app->getTemplate() . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, ['html', 'plg_system_jpdfgenerator']);

		//проверяем есть ли кастомный шаблон в запросе
		if (isset($request_data['template']))
		{
			$template_name = $request_data['template'];
		}

		//проверяем есть ли действие в запросе
		if (isset($request_data['action']))
		{
			$action = $request_data['action'];
		}

		//проверяем если ли кастомный шаблон
		if (file_exists($template_path_theme . DIRECTORY_SEPARATOR . $template_name))
		{
			$template = new FileLayout('template', $template_path_theme . DIRECTORY_SEPARATOR . $template_name);
			if (!file_exists($template_path_theme . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'data.php'))
			{
				echo Text::_('PLG_JPDFGENERATOR_TEMPLATE_DATA_NOT_FOUND');
				$app->close();
			}

			if (file_exists($template_path_theme . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'config.php'))
			{
				$config = include $template_path_theme . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'config.php';
			}

			$template_data = include $template_path_theme . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'data.php';
			$html          = $template->render($template_data);
		}
		else
		{

			//провереяем есть ли стандартные шаблоны
			if (file_exists($template_path_default . DIRECTORY_SEPARATOR . $template_name))
			{
				$template = new FileLayout('template', $template_path_default . DIRECTORY_SEPARATOR . $template_name);

				if (!file_exists($template_path_default . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'data.php'))
				{
					echo Text::_('PLG_JPDFGENERATOR_TEMPLATE_DATA_NOT_FOUND');
					$app->close();
				}

				if (file_exists($template_path_default . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'data.php'))
				{
					$config = include $template_path_theme . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'config.php';
				}

				$template_data = include $template_path_default . DIRECTORY_SEPARATOR . $template_name . DIRECTORY_SEPARATOR . 'data.php';
				$html          = $template->render($template_data);
			}
			else
			{
				//выкидываем ошибку, если шаблон вообще не найден
				echo Text::_('PLG_JPDFGENERATOR_TEMPLATE_NOT_FOUND');
				$app->close();
			}

		}

		$pdf = new JMpdf($html, $config);

		if ($action === 'stream')
		{
			$pdf->stream();
		}

		if ($action === 'download')
		{
			$pdf->download();
		}

		$app->close();

	}


}
