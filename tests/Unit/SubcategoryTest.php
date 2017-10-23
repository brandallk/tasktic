<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Task;

class SubcategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_Subcategory_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $this->assertDatabaseHas('subcategories', ['id' => $subcategory->id, 'name' => 'New Subcategory']);
        $this->assertInstanceOf(Subcategory::class, $subcategory);
    }

    /** @test */
    public function a_Subcategory_has_a_name_and_list_element_id()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory'); 
        
        $this->assertEquals('New Subcategory', $subcategory->name);
        $this->assertFalse($subcategory->list_element_id == null);
        $this->assertInternalType('string', $subcategory->list_element_id);
    }

    /** @test */
    public function a_Subcategory_belongs_to_a_Category()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $this->assertEquals('Category Name', $subcategory->category->name);
    }

    /** @test */
    public function a_new_Subcategory_gets_added_to_its_parent_TaskList_listElements()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $this->assertDatabaseHas(
            'list_elements',
            ['type' => 'subcategory', 'name' => 'New Subcategory']
        );

        $this->assertEquals(
            'New Subcategory',
            $list->listElements->where('unique_id', $subcategory->list_element_id)->first()->name
        );
    }

    /** @test */
    public function a_Subategory_can_add_a_Task()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $newTask = Task::newTask($subcategory, 'New Task');

        $this->assertDatabaseHas('tasks', ['subcategory_id' => $subcategory->id, 'name' => 'New Task']); 
    }

    /** @test */
    public function a_Subategory_can_access_its_Tasks()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $newTask = Task::newTask($subcategory, 'New Task');

        $this->assertEquals(
            $newTask->id,
            $subcategory->tasks->where('name', 'New Task')->first()->id
        );
    }

    /** @test */
    public function a_Subcategory_can_be_updated()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'New List');
        $category = Category::newCategory($list, 'New Category');
        $subcategory = Subcategory::newSubcategory($category, 'New Subcategory');
        
        $subcategory->updateSubcategory($subcategory, 'New Name');
        
        $this->assertEquals('New Name', $subcategory->name);
    }

    /** @test */
    public function a_Subcategory_can_be_deleted()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newSubcategory($category, 'Subcategory Name');
        
        Subcategory::deleteSubcategory($subcategory);
        
        $this->assertDatabaseMissing('subcategories', ['id' => $subcategory->id, 'name' => 'Subcategory Name']);
    }

    /** @test */
    public function a_default_Subcategory_can_be_created()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newDefaultSubcategory($category);
        
        $this->assertDatabaseHas('subcategories', ['id' => $subcategory->id]);
    }

    /** @test */
    public function a_default_Subcategory_is_named_null()
    {
        $user = factory(User::class)->create();
        $list = TaskList::newTaskList($user, 'List Name');
        $category = Category::newCategory($list, 'Category Name');
        $subcategory = Subcategory::newDefaultSubcategory($category);

        $this->assertEquals(null, $subcategory->name);
    }
}
