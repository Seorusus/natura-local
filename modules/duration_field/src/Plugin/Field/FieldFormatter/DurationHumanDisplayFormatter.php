<?php

namespace Drupal\duration_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a formatter for the duration field type
 *
 * @FieldFormatter(
 *   id = "duration_human_display",
 *   label = @Translation("Human Friendly"),
 *   field_types = {
 *     "duration"
 *   }
 * )
 */
 class DurationHumanDisplayFormatter extends FormatterBase
{
	/**
	 * {@inheritdoc}
	 */
	public function settingsSummary()
	{
		$summary = array();

		$settings = $this->getSettings();

		$summary[] = $this->t('Displays the duration in a human-friendly format. Words are shown in @text_length form, and separated by @separator', ['@text_length' => $this->getHumanFriendlyLabel($settings['text_length'], FALSE), '@separator' => $this->getHumanFriendlyLabel($settings['separator'], FALSE)]);

		return $summary;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function defaultSettings()
	{
		return [
			'text_length' => 'full',
			'separator' => 'space',
		] + parent::defaultSettings();
	}

	/**
	 * {@inheritdoc}
	 */
	public function settingsForm(array $form, FormStateInterface $form_state)
	{
		$element['text_length'] = [
			'#title' => t('Text length'),
			'#type' => 'select',
			'#options' => [
				'full' => $this->getHumanFriendlyLabel('full'),
				'short' => $this->getHumanFriendlyLabel('short'),
			],
			'#default_value' => $this->getSetting('text_length'),
		];

		$element['separator'] = [
			'#title' => $this->t('Separator'),
			'#type' => 'select',
			'#options' => [
				'space' => $this->getHumanFriendlyLabel('space'),
				'hyphen' => $this->getHumanFriendlyLabel('hyphen'),
				'comma' => $this->getHumanFriendlyLabel('comma'),
				'newline' => $this->getHumanFriendlyLabel('newline'),
			],
			'#default_value' => $this->getSetting('separator'),
		];

		return $element;
	}

	/**
	 * {@inheritdoc}
	 */
	public function viewElements(FieldItemListInterface $items, $langcode)
	{
		$element = [];

		$granularity = $this->getFieldSetting('granularity');

		foreach($items as $delta => $item)
		{
			$duration = new \DateInterval($item->value);

			$output = [];
			if($granularity['year'] && $years = $duration->format('%y'))
			{
				$output[] = $this->getTimePeriod('year', $years);
			}

			if($granularity['month'] && $months = $duration->format('%m'))
			{
				$output[] = $this->getTimePeriod('month', $months);
			}

			if($granularity['day'] && $days = $duration->format('%d'))
			{
				$output[] = $this->getTimePeriod('day', $days);
			}

			if($granularity['hour'] && $hours = $duration->format('%h'))
			{
				$output[] = $this->getTimePeriod('hour', $hours);
			}

			if($granularity['minute'] && $minutes = $duration->format('%i'))
			{
				$output[] = $this->getTimePeriod('minute', $minutes);
			}

			if($granularity['second'] && $seconds = $duration->format('%s'))
			{
				$output[] = $this->getTimePeriod('second', $seconds);
			}

			$value = count($output) ? implode($this->getSeparator(), $output) : '0';

			// Render each element as markup.
			$element[$delta] = [
				'#markup' => $value,
			];
		}

		return $element;
	}

	/**
	 * Converts a key to a human readable value
	 *
	  * @param string $key
	  *   The machine readable name to be converted.
	  *  @param bool $capitalize
	  *   Whether or not the return value should be capitalized
	  *
	  * @return string
	  *   The converted value, if a mapping exists, otherwise the original key
	  */
	protected function getHumanFriendlyLabel($key, $capitalize = TRUE)
	{
		if($capitalize)
		{
			$values = [
				'full' => t('Full'),
				'short' => t('Short'),
				'space' => t('Spaces'),
				'hyphen' => t('Hyphens'),
				'comma' => t('Commas'),
				'newline' => t('New lines'),
			];
		}
		else
		{
			$values = [
				'full' => t('full'),
				'short' => t('short'),
				'space' => t('spaces'),
				'hyphen' => t('hyphens'),
				'comma' => t('commas'),
				'newline' => t('new lines'),
			];
		}

		return isset($values[$key]) ? $values[$key] : $key;
	}

	/**
	 * Converts the key for a separator between values to
	 * the actual separator to be used
	 *
	 * @return string
	 *   The value to be inserted between returned elements
	 */
	protected function getSeparator()
	{
		$separators = [
			'space' => ' ',
			'hyphen' => ' - ',
			'comma' => ', ',
			'newline' => '<br />',
		];

		return $separators[$this->getSetting('separator')];
	}

	/**
	 * Returns a human-friendly value for a given time period key
	 *
	 * @param string $type
	 *   The type of the humann readable value to retrieve
	 *
	 * @param int $value
	 *   The amount for that time period
	 *
	 * @return string
	 *   The translateable human-friendly count of the given type
	 */
	protected function getTimePeriod($type, $value)
	{
		$text_length = $this->getSetting('text_length');
		if($type == 'year')
		{
			if($text_length == 'full')
			{
				return $this->formatPlural($value, '1 year', '@count years');
			}
			else
			{
				return $this->formatPlural($value, '1 yr', '@count yr');
			}
		}
		elseif($type == 'month')
		{
			if($text_length == 'full')
			{
				return $this->formatPlural($value, '1 months', '@count months');
			}
			else
			{
				return $this->formatPlural($value, '1 mo', '@count mo');
			}
		}
		elseif($type == 'day')
		{
			return $this->formatPlural($value, '1 day', '@count days');
		}
		elseif($type == 'hour')
		{
			if($text_length == 'full')
			{
				return $this->formatPlural($value, '1 hour', '@count hours');
			}
			else
			{
				return $this->formatPlural($value, '1 hr', '@count hr');
			}
		}
		elseif($type == 'minute')
		{
			if($text_length == 'full')
			{
				return $this->formatPlural($value, '1 minute', '@count minutes');
			}
			else
			{
				return $this->formatPlural($value, '1 min', '@count min');
			}
		}
		elseif($type == 'second')
		{
			if($text_length == 'full')
			{
				return $this->formatPlural($value, '1 second', '@count seconds');
			}
			else
			{
				return $this->formatPlural($value, '1 s', '@count s');
			}
		}
	}
}
