<?php function botascopia_module_notice($data) {

  $defaults = [
    'closable' => false,
    'type' => 'info',
    'modifiers' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('notice', 'notice-' . $data->type, $data->modifiers);

  if ($data->closable) {
    $data->modifiers[] = 'is-closable';
  }

  echo '<div class="' . implode(' ', $data->modifiers) . '">';

    if ($data->title) {
      echo '<strong class="notice-title">' . $data->title . '</strong> ';
    }

    echo '<span class="notice-text">' . $data->text . '</span>';

    if ($data->closable) {
      printf(
        '<button class="notice-close">%s</button>',
        get_botascopia_module('icon', ['icon' => 'close'])
      );
    }

  echo '</div>';

}
