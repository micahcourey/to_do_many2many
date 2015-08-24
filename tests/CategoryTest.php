<?php


    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Category.php";

    $server = 'mysql:host=localhost;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CategoryTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Category::deleteAll();
            Task::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "Work stuff";
            $test_Category = new Category($name);

            //Act
            $result = $test_Category->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_Category = new Category($name, $id);

            //Act
            $result = $test_Category->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            //Arrange
            $name = "Work stuff";
            $test_Category = new Category($name);
            $test_Category->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals($test_Category, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $name = "Work stuff";
            $name2 = "Home stuff";
            $test_Category = new Category($name);
            $test_Category->save();
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            //Act
            $result = Category::getAll();

            //Assert
            $this->assertEquals([$test_Category, $test_Category2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Wash the dog";
            $name2 = "Home stuff";
            $test_Category = new Category($name);
            $test_Category->save();
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            //Act
            Category::deleteAll();
            $result = Category::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Wash the dog";
            $name2 = "Home stuff";
            $test_Category = new Category($name);
            $test_Category->save();
            $test_Category2 = new Category($name2);
            $test_Category2->save();

            //Act
            $result = Category::find($test_Category->getId());

            //Assert
            $this->assertEquals($test_Category, $result);
        }

        function testGetTasks()
        {
          //Arrange
          $name = "Work stuff";
          $id = null;
          $test_category = new Category($name, $id);
          $test_category->save();

          $test_category_id = $test_category->getId();

          $description = "Email client";
          $due_date = "tomorrow";
          $test_task = new Task($description, $due_date, $id, $test_category_id);
          $test_task->save();

          $description2 = "Meet with boss";
          $due_date2 = "tomorrow";
          $test_task2 = new Task($description2, $due_date2, $id, $test_category_id);
          $test_task2->save();

          //Act
          $result = $test_category->getTasks();

          //Assert
          $this->assertEquals([$test_task, $test_task2], $result);
        }

        function testUpdate()
        {
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $new_name = "Home stuff";

            $test_category->update($new_name);

            $this->assertEquals("Home stuff", $test_category->getName());
        }

        function testDelete()
        {
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Home stuff";
            $test_category2 = new Category($name2, $id);
            $test_category2->save();

            $test_category->delete();

            $this->assertEquals([$test_category2], Category::getAll());
        }

        function testDeleteCategoryTasks()
        {
            $name = "Work stuff";
            $id = null;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Build website";
            $category_id = $test_category->getId();
            $due_date = "tommorrow"; 
            $test_task = new Task($description, $due_date, $id, $category_id);
            $test_task->save();

            $test_category->delete();

            $this->assertEquals([], Task::getAll());
        }
    }

 ?>
