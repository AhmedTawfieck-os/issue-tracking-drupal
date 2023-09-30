<?php 

function second_issue_tracking_install() {
    $issue_type = node_type_set_defaults('issue tracking');
    node_add_body_field($issue_type, t('Short description'));
    node_add_field($issue_type, 'field_assignee', t('Assignee'), 'user', 'autocomplete');
    node_add_field($issue_type, 'field_watchers', t('Watchers'), 'user', 'checkboxes');
    node_add_field($issue_type, 'field_issue_type', t('Issue type'), 'taxonomy_term_reference', 'select');
    node_add_field($issue_type, 'field_priority', t('Priority'), 'taxonomy_term_reference', 'select');
    node_add_field($issue_type, 'field_status', t('Status'), 'taxonomy_term_reference', 'select');
    node_add_field($issue_type, 'field_due_date', t('Due date'), 'date', 'date_popup');
    node_type_save($issue_type);


        // Create Issue type vocabulary.
        $vocabulary = new stdClass();
        $vocabulary->name = 'Issue type';
        $vocabulary->machine_name = 'issue_type';
        $vocabulary->description = 'The type of issue.';
        $vocabulary->module = 'taxonomy';
        taxonomy_vocabulary_save($vocabulary);
      
        // Create Issue type terms.
        $terms = array(
          'New feature',
          'Change',
          'Task',
          'Bug',
          'Improvement',
        );
        foreach ($terms as $term_name) {
          $term = new stdClass();
          $term->name = $term_name;
          $term->vid = $vocabulary->vid;
          taxonomy_term_save($term);
        }
      
        // Create Priority vocabulary.
        $vocabulary = new stdClass();
        $vocabulary->name = 'Priority';
        $vocabulary->machine_name = 'priority';
        $vocabulary->description = 'The priority of the issue.';
        $vocabulary->module = 'taxonomy';
        taxonomy_vocabulary_save($vocabulary);
          
        // Create Priority terms.
        $terms = array(
            'Critical',
            'High',
            'Low',
            'Trivial',
        );
        foreach ($terms as $term_name) {
            $term = new stdClass();
            $term->name = $term_name;
            $term->vid = $vocabulary->vid;
            taxonomy_term_save($term);
            }
          
        // Create Status vocabulary.
        $vocabulary = new stdClass();
        $vocabulary->name = 'Status';
        $vocabulary->machine_name = 'status';
        $vocabulary->description = 'The status of the issue.';
        $vocabulary->module = 'taxonomy';
        taxonomy_vocabulary_save($vocabulary);
              
        // Create Status terms.
        $terms = array(
            'To Do',
            'In Progress',
            'In Review',
            'Done',
                );
        foreach ($terms as $term_name) {
            $term = new stdClass();
            $term->name = $term_name;
            $term->vid = $vocabulary->vid;
                  taxonomy_term_save($term);
                }
  }  

?>