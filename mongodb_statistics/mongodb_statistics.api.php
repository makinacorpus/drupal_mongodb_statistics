<?php

/**
 * hook_mongodb_statistics_firstvisits
 *
 * Hook used in the cron synchonisation of user counters
 * 
 * We are giving you the list of nodes visited by some users since last synchonisations.
 * 
 * @param $visits is an array indexed by uid, containing for each uid the list (array) of newly visited nodes (nid)
 * @return unknown_type
 */
function hook_mongodb_statistics_firstvisits($visits) {
  
  foreach($visits as $uid => $nid_array) {
    foreach($nid_array as $k => $nid) {
      $node = node_load($nid);
      // Award the points.
      userpoints_userpointsapi(array(
        'uid' => $uid,
        'points' => variable_get("labs_vis_default", 0),
        'moderate' => FALSE,
        'time_stamp' => REQUEST_TIME,
        'operation' => 'userpoints_1st_visit',
        'entity_id' => $nid,
        'entity_type' => 'node',
        'description' => t('visited : @title', array('@title' => $node->title)),
        //'tid' => variable_get('my_point_category'),
        'display' => FALSE,
      ));
    }
  }
  
}

/**
 * hook_mongodb_statistics_node_report
 *
 * Hook used to render a report on the node statistics tab
 * 
 * WARNING: returned boolean is important!
 * 
 * We are giving you the current node, and the current array of content rendered on this tab.
 * 
 * @param $node: the current node
 * @param: $build: the current render array of this tab 
 * @return boolean: TRUE if you had anything to report FALSE instead
 */
function hook_mongodb_statistics_node_report($node,&$build) {
  if (!$data = my_custom_stats_data($node->nid)) {
    return FALSE;
  }
  $build['custom_statistics_report_title'] = array(
    '#type' => 'markup',
    '#markup' => '<h3>'.t('My custom report.').'</h3>',
  );
  $build['custom_statistics_report_table'] = array(
    '#type' => 'markup',
    '#markup' => '<div>'.t('Here I should build a table with $data.').'</div>',
  );
  return TRUE;
}
