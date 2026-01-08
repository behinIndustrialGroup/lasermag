<?php

namespace Behin\SimpleWorkflow\Controllers\Core;

use App\Http\Controllers\Controller;
use Behin\SimpleWorkflow\Models\Core\DashboardItem;
use Illuminate\Http\Request;

class DashboardItemController extends Controller
{
    public function index()
    {
        $items = DashboardItem::orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        return view('SimpleWorkflowView::Core.DashboardItem.index', compact('items'));
    }

    public function create()
    {
        return view('SimpleWorkflowView::Core.DashboardItem.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'access_key' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DashboardItem::create($validated);

        return redirect()->route('simpleWorkflow.dashboard-items.index')->with('success', 'Dashboard item created successfully.');
    }

    public function edit(DashboardItem $dashboard_item)
    {
        return view('SimpleWorkflowView::Core.DashboardItem.edit', [
            'item' => $dashboard_item,
        ]);
    }

    public function update(Request $request, DashboardItem $dashboard_item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'access_key' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $dashboard_item->update($validated);

        return redirect()->route('simpleWorkflow.dashboard-items.index')->with('success', 'Dashboard item updated successfully.');
    }

    public function destroy(DashboardItem $dashboard_item)
    {
        $dashboard_item->delete();

        return redirect()->route('simpleWorkflow.dashboard-items.index')->with('success', 'Dashboard item deleted successfully.');
    }
}
