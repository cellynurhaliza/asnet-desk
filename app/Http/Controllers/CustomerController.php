<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    private array $serviceTypes = [
        'Internet',
        'Local Loop',
    ];

    private array $statuses = [
        'Active',
        'Banned',
        'Disabled',
        'Inactive',
        'Limited',
        'Suspended',
    ];

    private array $accessMethods = [
        'PPPoE',
        'Static IP',
        'Q-in-Q',
        'Trunk',
    ];

    private array $connectionTypes = [
        'Modem',
        'Core to core',
    ];

    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'ilike', "%{$search}%")
                        ->orWhere('pic', 'ilike', "%{$search}%")
                        ->orWhere('cid', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('phone_number', 'ilike', "%{$search}%")
                        ->orWhere('ip_address', 'ilike', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('pages.customers.create', [
            'serviceTypes' => $this->serviceTypes,
            'statuses' => $this->statuses,
            'AccessMethods' => $this->accessMethods,
            'ConnectionTypes' => $this->connectionTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:customer,username',
            'pic' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customer,email',
            'phone_number' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string',
            'service_type' => [
                'required',
                Rule::in($this->serviceTypes),
            ],
            'coordinates' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => [
                'required',
                Rule::in($this->statuses),
            ],
            'ip_address' => 'nullable|ip',
            'vlan' => 'nullable|string|max:100',
            'pop_location' => 'nullable|string|max:255',
            'port_pon' => 'nullable|string|max:100',
            'cid' => 'nullable|string|max:100',
            'user_location' => 'nullable|string|max:255',
            'bandwidth_value' => 'nullable|numeric|min:0',
            'bandwidth_unit' => ['required_with:bandwidth_value', Rule::in(['Kbps', 'Mbps', 'Gbps'])],
            'media' => ['nullable', Rule::in(['Modem', 'Core to Core'])],
            'access_method' => ['nullable', Rule::in($this->accessMethods)],
            'connection_type' => ['nullable', Rule::in($this->connectionTypes)],
        ]);

        $validated['password'] = Crypt::encryptString($validated['password']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('customers', 'public');
        }

        $validated['bandwidth'] = !empty($validated['bandwidth_value'])
            ? $validated['bandwidth_value'] . ' ' . $validated['bandwidth_unit']
            : null;

        unset($validated['bandwidth_value'], $validated['bandwidth_unit']);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('pages.customers.edit', [
            'customer' => $customer,
            'serviceTypes' => $this->serviceTypes,
            'statuses' => $this->statuses,
            'AccessMethods' => $this->accessMethods,
            'ConnectionTypes' => $this->connectionTypes,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customer', 'username')->ignore($customer->id),
            ],
            'pic' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customer', 'email')->ignore($customer->id),
            ],
            'phone_number' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string',
            'service_type' => [
                'required',
                Rule::in($this->serviceTypes),
            ],
            'coordinates' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => [
                'required',
                Rule::in($this->statuses),
            ],
            'ip_address' => 'nullable|ip',
            'vlan' => 'nullable|string|max:100',
            'pop_location' => 'nullable|string|max:255',
            'port_pon' => 'nullable|string|max:100',
            'cid' => 'nullable|string|max:100',
            'user_location' => 'nullable|string|max:255',
            'bandwidth' => 'nullable|string|max:100',
            'access_method' => ['nullable', Rule::in($this->accessMethods)],
            'connection_type' => ['nullable', Rule::in($this->connectionTypes)],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Crypt::encryptString(
                $validated['password']
            );
        }

        if ($request->hasFile('photo')) {
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('customers', 'public');
        }

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->photo) {
            Storage::disk('public')->delete($customer->photo);
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function show(Customer $customer)
    {
        try {
            $customerPassword = Crypt::decryptString($customer->password);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            $customerPassword = null;
        }

        return view('pages.customers.show', compact(
            'customer',
            'customerPassword'
        ));
    }
}
