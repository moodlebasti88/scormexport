<?php
/*
 * <p>This file contains all sql Querys</p>
 * 
 * @author Bastian Rosenfelder
 * 
 */

 /**
  * Summary of validateCourse
  * 
  * <p>checks if the course exists in the database</p>
  * 
  * @param int $courseid id of the requested course
  * @return array[] $course array containing information about the course; false if course does not exist
  */
 function validateCourse($courseid)
 {

    global $DB;

    $course = $DB->get_record('course', array('id' => $courseid));
    return $course;

 }

 /**
  * Summary of getLessonsByCourse
  * 
  * <p>returns all lessons for the course with the given id</p>
  * 
  * @param int $courseid unique course identifier
  * @return array[] array containing all lessons for the course with $courseid
  */
 function getLessonsByCourse($courseid)
 {
    global $DB;
    $results = $DB->get_records_sql('SELECT * FROM {lesson} WHERE course = ?', array($courseid));
    return $results;
 }
 
 
 /**
  * Summary of getQuizzesByCourse
  * 
  * <p>returns all tests for the course with the given id</p>
  * 
  * @param int $courseid  unique course identifier
  * @return array[] array containing all tests for the course with $courseid
  */
 function getQuizzesByCourse($courseid)
 {
     global $DB;
     $results = $DB->get_records_sql('SELECT * FROM {quiz} WHERE course = ?', array($courseid));
     return $results;
     
 }

 
 /**
  * Summary of getQuizRecordById
  * 
  * <p>returns the row which contains the given id</p>
  * 
  * @param int $quizId unique quiz identifier
  * @return array[] array containing the requested row; false if $quizid does not exist
  */
 function getQuizRecordById($quizId)
 {
     global $DB;
     
     $results = $DB->get_record('quiz', array('id' => $quizId));
     
     return $results;
       
 }
 
 function getTextPagesByCourse($id)
 {
     global $DB;
     
     $results = $DB->get_records_sql('SELECT * FROM {page} WHERE course = ?',array($id));
     
     return $results;
 }
 
 function getTextPageById($id)
 {
     global $DB;
     
     $record = $DB->get_record('page', array('id' => $id));
     
     return $record;
 }
 
?>
