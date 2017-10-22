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

    // /** @test */
    // public function a_Category_belongs_to_a_TaskList()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_new_Category_is_added_to_its_TaskList_elements_array()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_Category_can_add_a_Subcategory()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_Category_can_access_its_Subcategories()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_Category_can_be_updated()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_Category_can_be_deleted()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_default_Category_can_be_created()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_default_Category_is_named_null()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }

    // /** @test */
    // public function a_default_Category_has_a_Subcategory_named_null()
    // {
    //     //given 
    //     //when 
    //     //then 
    // }
}