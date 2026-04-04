<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AutoPool;
use App\Models\AutoPoolCategory;
use App\Models\ProductPackage;

class AutoPoolIncomeController extends Controller
{
  public function autoPoolCategories()
    {
        $categories = AutoPoolCategory::with('package')->orderBy('id', 'asc')->get();
        
        // Fetch ALL Capping/Package products
        $allPackages = ProductPackage::where('is_package_product', 1)->get();
        
        // Get IDs of packages that are already assigned to ANY category
        $usedPackageIds = AutoPoolCategory::whereNotNull('product_package_id')->pluck('product_package_id')->toArray();
        
        return view('admin.auto-pool.categories', compact('categories', 'allPackages', 'usedPackageIds'));
    }

    // 2. Store Category
    public function storeAutoPoolCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:auto_pool_categories,category_name',
            'product_package_id' => 'nullable|exists:product-package,id|unique:auto_pool_categories,product_package_id',
            'pv_required' => 'nullable|integer|min:0',
            'direct_count' => 'nullable|integer|min:0',
            'each_direct_pv' => 'required_if:direct_count,>,0|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        AutoPoolCategory::create($request->all());
        return back()->with('success', 'Category created successfully with entry conditions.');
    }

    // 3. Update Category
    public function updateAutoPoolCategory(Request $request, $id)
    {
        $category = AutoPoolCategory::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255|unique:auto_pool_categories,category_name,' . $category->id,
            'product_package_id' => 'nullable|exists:product-package,id|unique:auto_pool_categories,product_package_id,' . $category->id,
            'pv_required' => 'nullable|integer|min:0',
            'direct_count' => 'nullable|integer|min:0',
            'each_direct_pv' => 'required_if:direct_count,>,0|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $category->update($request->all());
        return back()->with('success', 'Category updated successfully.');
    }
    // 4. Delete Category
    public function destroyAutoPoolCategory($id)
    {
        AutoPoolCategory::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
    
    public function manageAutoPools()
    {
        $pools = AutoPool::orderBy('pool_level', 'asc')->get();
        $categories = AutoPoolCategory::where('is_active', 1)->get();
        return view('admin.auto-pool.index', compact('pools', 'categories'));
    }

    public function storeAutoPool(Request $request)
    {
        $request->validate([
            'pool_level' => 'required|integer|unique:auto_pools,pool_level',
            'rank_name' => 'required|string|max:255',
            'required_pv' => 'required|integer',
            'income' => 'required|numeric',
            'category_id' => 'required|exists:auto_pool_categories,id',
        ]);

        AutoPool::create($request->all());
        return back()->with('success', 'Auto Pool created successfully.');
    }

    public function updateAutoPool(Request $request, $id)
    {
        $pool = AutoPool::findOrFail($id);

        $request->validate([
            'pool_level' => 'required|integer|unique:auto_pools,pool_level,' . $pool->id,
            'rank_name' => 'required|string|max:255',
            'required_pv' => 'required|integer',
            'income' => 'required|numeric',
            'category_id' => 'required|exists:auto_pool_categories,id',
        ]);

        $pool->update($request->all());
        return back()->with('success', 'Auto Pool updated successfully.');
    }

    public function destroyAutoPool($id)
    {
        AutoPool::findOrFail($id)->delete();
        return back()->with('success', 'Auto Pool deleted successfully.');
    }
}
