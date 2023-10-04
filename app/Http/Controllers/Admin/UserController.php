<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('backend.users.index');
    }

    public function users_api(Request $request)
    {
        $columns = [
            1 => 'id',
            2 => 'username',
            3 => 'first_name',
            4 => 'last_name',
            5 => 'phone',
            6 => 'email',
        ];

        $search = [];

        $totalData = User::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = User::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $users = User::where('id', 'LIKE', "%{$search}%")
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = User::where('id', 'LIKE', "%{$search}%")
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = [];

        if (!empty($users)) {
            // providing a dummy id instead of database ids
            $ids = $start;

            foreach ($users as $user) {
                $nestedData['id'] = $user->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['first_name'] = $user->first_name;
                $nestedData['last_name'] = $user->last_name;
                $nestedData['username'] = $user->username;
                $nestedData['phone'] = $user->phone;
                $nestedData['email'] = $user->email;
                $nestedData['avatar'] = $user->avatar;

                $data[] = $nestedData;
            }
        }

        if ($data) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'code' => 200,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'message' => 'Internal Server Error',
                'code' => 500,
                'data' => [],
            ]);
        }
    }

    public function store(Request $request)
    {
        $userID = $request->id;

        $request->validate([
            'username' => 'required|unique:users,username,' . $userID,
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users,phone,' . $userID,
            'email' => 'required|email|unique:users,email,' . $userID,
        ]);

        if ($userID) {
            // update the value
            $users = User::updateOrCreate([
                'id' => $userID
            ], [
                    'username' => $request->username,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email
                ]
            );

            // user updated
            return response()->json('Updated');
        } else {
            // create new one if email is unique
            $userEmail = User::where('email', $request->email)->first();

            if (empty($userEmail)) {
                $users = User::updateOrCreate([
                    'id' => $userID
                ], [
                        'username' => $request->username,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'password' => bcrypt(Str::random(10))
                    ]
                );

                // user created
                return response()->json('Created');
            } else {
                // user already exist
                return response()->json(['message' => "already exits"], 422);
            }
        }
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return 'User deleted';
    }

    public function edit_profile(Request $request)
    {
        return view('backend.users.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update_profile(Request $request)
    {
        //  return $request->avatar;
        $request->validate([
            'avatar' => ['nullable'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
            'phone' => ['required', Rule::unique(User::class)->ignore($request->user()->id)],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]);
        $user = Auth::user();
        $oldAvatarPath = $user->avatar;

        $dataToUpdate = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            // Store the new avatar and update the path
            $newAvatarPath = $request->file('avatar')->store('avatar', 'public');

            // Delete the old avatar if it exists
            if ($oldAvatarPath !== null && Storage::disk('public')->exists($oldAvatarPath)) {
                Storage::disk('public')->delete($oldAvatarPath);
            }

            $dataToUpdate['avatar'] = $newAvatarPath;
        }

        $user->update($dataToUpdate);
        $user->refresh();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function edit_security(Request $request)
    {
        return view('backend.users.security', [
            'user' => $request->user(),
        ]);
    }

    public function update_security(Request $request)
    {
        $request->validate([
            'currentPassword' => ['required', new PasswordChangeValidationRule],
            'newPassword' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->newPassword)
        ]);
        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }
}
