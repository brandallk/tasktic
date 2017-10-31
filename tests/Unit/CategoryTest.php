<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_Category_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Category']);
        $this->assertInstanceOf(Category::class, $category);
    }

    /** @test */
    public function a_Category_has_a_name_and_list_element_id()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category'); 
        
        $this->assertEquals('New Category', $category->name);
        $this->assertFalse($category->list_element_id == null);
        $this->assertInternalType('string', $category->list_element_id);
    }

    /** @test */
    public function a_Category_belongs_to_a_TaskList()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        
        $this->assertEquals('List Name', $category->taskList->name);
    }

    /** @test */
    public function a_new_Category_gets_added_to_its_TaskList_listElements()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'category', 'name' => 'New Category']
        );

        $this->assertEquals(
            'New Category',
            $list->listElements->where('unique_id', $category->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_Category_can_add_a_Subcategory()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        
        $newSubcategory = Subcategory::newSubcategory($category, 'New Subcategory');

        $this->assertDatabaseHas('subcategories', ['category_id' => $category->id, 'name' => 'New Subcategory']); 
    }

    /** @test */
    public function a_Category_can_access_its_Subcategories()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        
        $newSubcategory = Subcategory::newSubcategory($category, 'New Subcategory');

        $this->assertEquals(
            $newSubcategory->id,
            $category->subcategories->where('name', 'New Subcategory')->first()->id
        );
    }

    /** @test */
    public function a_Category_can_be_updated()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        
        $category->updateCategory('New Name');
        
        $this->assertEquals('New Name', $category->name);

        // The parent TaskList's corresponding ListElement should be updated
        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'category', 'name' => 'New Category']
        );
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'category', 'name' => 'New Name']
        );
    }

    /** @test */
    public function a_Category_can_be_deleted()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        
        Category::deleteCategory($category);
        
        $this->assertDatabaseMissing(
            'categories',
            ['id' => $category->id, 'name' => 'Category Name']
        );

        $this->assertDatabaseMissing(
            'list_elements',
            ['type' => 'category', 'name' => 'Category Name']
        );
    }

    /** @test */
    public function a_default_Category_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newDefaultCategory($list);
        
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function a_default_Category_is_named_null()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newDefaultCategory($list);

        $this->assertEquals(null, $category->name);
    }

    /** @test */
    public function a_default_Category_has_a_Subcategory_named_null()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newDefaultCategory($list);

        $this->assertDatabaseHas('subcategories', ['category_id' => $category->id, 'name' => null]);
        $this->assertEquals(null, $category->subcategories()->first()->name);
    }
}
