<?php



$access = new AccessData();

$access->query("ALTER TABLE " . StudyPressDB::getTableNameCourse() ." ADD ".StudyPressDB::COL_PICTURE_COURSE ." VARCHAR(255)");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameActivity() ." ADD ".StudyPressDB::COL_ORDER_ACTIVITY ." INT NOT NULL");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameActivity() ." CHANGE notes " . StudyPressDB::COL_TAGS_ACTIVITY . "  longtext");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameVisite() ." CHANGE lesson_id " . StudyPressDB::COL_ID_ACTIVITY_VISITE . " BIGINT UNSIGNED");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameVisite() ." CHANGE user_visite " . StudyPressDB::COL_ID_USER_VISITE . " BIGINT UNSIGNED");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameQuestions() ." CHANGE col " . StudyPressDB::COL_TYPE_QUESTION . " VARCHAR(25)");

$access->query("UPDATE ". StudyPressDB::getTableNameQuestions() . " SET " . StudyPressDB::COL_TYPE_QUESTION ." = 'multiple'");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameActivity() ." CHANGE picture_url " . StudyPressDB::COL_PICTURE_ACTIVITY . " text");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameActivity() ." CHANGE activity_id " . StudyPressDB::COL_ID_ACTIVITY . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameSlide() ." CHANGE slide_id " . StudyPressDB::COL_ID_SLIDE . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableName_CourseUsers() ." CHANGE ID " . StudyPressDB::COL_ID_USERS_USERS_N_COURSE . "  BIGINT UNSIGNED");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameVisite() ." CHANGE visite_id " . StudyPressDB::COL_ID_VISITE . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameDomain() ." CHANGE domain_id " . StudyPressDB::COL_ID_DOMAIN . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameRateQuality() ." CHANGE ID " . StudyPressDB::COL_ID_USER_RATE_QUALITY . "  BIGINT UNSIGNED");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameRateQuality() ." CHANGE rate_id " . StudyPressDB::COL_ID_RATE_QUALITY . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameRateDomain() ." CHANGE ID " . StudyPressDB::COL_ID_USER_RATE_DOMAIN . "  BIGINT UNSIGNED");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameRateDomain() ." CHANGE rate_domain_id " . StudyPressDB::COL_ID_RATE_DOMAIN . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameQuestions() ." CHANGE question_id " . StudyPressDB::COL_ID_QUESTION . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNamePropositions() ." CHANGE proposition_id " . StudyPressDB::COL_ID_PROPOSITION . "  BIGINT UNSIGNED AUTO_INCREMENT");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameResultQuiz() ." CHANGE ID " . StudyPressDB::COL_ID_USER_RESULT . "  BIGINT UNSIGNED");

$access->query("ALTER TABLE " . StudyPressDB::getTableNameResultQuiz() ." CHANGE result_id " . StudyPressDB::COL_ID_RESULT . "  BIGINT UNSIGNED AUTO_INCREMENT");

$manager = new CourseManager();
$courses = $manager->getAll();
foreach ($courses as $course) {
    $manager->update($course->getId(),$course);
}


