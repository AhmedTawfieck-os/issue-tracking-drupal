<?php 

/* 
    implements hook_form method
*/
function second_issue_tracking_form($form, &$form_state) {
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#required' => TRUE,
    );
    $form['body'] = array(
      '#type' => 'text_format',
      '#title' => t('Short description'),
      '#required' => TRUE,
    );
    $form['field_assignee'] = array(
      '#type' => 'textfield',
      '#title' => t('Assignee'),
      '#autocomplete_path' => 'user/autocomplete',
    );
    $form['field_watchers'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Watchers'),
      '#options' => user_get_users(),
    );
    $form['field_issue_type'] = array(
      '#type' => 'taxonomy_term_reference',
      '#title' => t('Issue type'),
      '#required' => TRUE,
      '#taxonomy_vocabulary_machine_name' => 'issue_type',
    );
    $form['field_priority'] = array(
      '#type' => 'taxonomy_term_reference',
      '#title' => t('Priority'),
      '#required' => TRUE,
      '#taxonomy_vocabulary_machine_name' => 'priority',
    );
    $form['field_status'] = array(
      '#type' => 'taxonomy_term_reference',
      '#title' => t('Status'),
      '#required' => TRUE,
      '#taxonomy_vocabulary_machine_name' => 'status',
    );
    $form['field_due_date'] = array(
      '#type' => 'date_popup',
      '#title' => t('Due date'),
    );
    return $form;
  }

  /* 
    implements hook_submit
  */
  function second_issue_tracking_submit($form, &$form_state) {
    $node = new stdClass();
    $node->type = 'issue';
    $node->title = $form_state['values']['title'];
    $node->body = $form_state['values']['body'];
    $node->uid = $GLOBALS['user']->uid;
    $node->field_assignee[LANGUAGE_NONE][0]['target_id'] = $form_state['values']['field_assignee'];
    for($i=0; $i<count($form_state['values']['field_watchers']) ; $i++){
    $node->field_watchers[LANGUAGE_NONE][$i]['target_id']= $form_state['values']['field_watchers'][$i];    
    }
    $node->field_assignee[LANGUAGE_NONE][0]['target_id'] = $form_state['values']['field_duedate'];
  }

    /*
        implements hook_block_info()
    */
    function second_issue_tracking_block_info() {
        $blocks = array();
        $blocks['latest_issues'] = array(
          'info' => t('Latest Issues'),
          'cache' => DRUPAL_CACHE_PER_ROLE,
        );
        return $blocks;
      }

    /*  
        implements hook_blook_view()
    */
    function second_issue_tracking_block_view($delta = '') {
        $block = array();
        switch ($delta) {
          case 'latest_issues':
            $uid = $GLOBALS['user']->uid;
            $query = db_query("SELECT n.title, n.body, n.issue_type, n.priority, n.status, n.duedate
                            FROM {node} n INNER JOIN {assignee} a ON n.nid = a.entity_id WHERE a.assignee_target_id = :uid AND n.type = 'issue_tracking' DESC LIMIT 3", array(':uid' => $uid));
            $output = '<ul>';
            foreach ($query as $row) {
              $output .= '<li><a href="/node/' . $row->nid . '">' . $row->title . '</a></li>'. '<br/>' .
                            $row->body. '<br/>' . $row->issue_type. '<br/>' . $row->priority . '<br/>' . 
                            $row->status . '<br/>' . $row->duedate ;
            }
            $output .= '<li><a href="/my-issues">View last 3 issues</a></li>';
            $output .= '</ul>';
            $block['content'] = array(
              '#markup' => $output,
            );
            break;
        }
        return $block;  //The block can be placed wherever the admin user like
    }
  

?>