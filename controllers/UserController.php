<?php

class UserController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->isAdmin()) {
            $users = User::all();
        } else {
            $users = User::whereId(Auth::id())->get();
        }

        return Response::json($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (Auth::user()->isAdmin()) {
            $user = new User(Input::all());
            $user->save();
            return Response::json($user);
        }

        return Response::json(['error' => 'Not authorized.'], 403);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if ($id === 'me') {
            $id = Auth::id();
        }

        if ($id === Auth::id() || Auth::user()->isAdmin()) {
            $user = User::find($id);
            if (!is_null($user)) {
                return Response::json($user->toApiArray(), 200, [], JSON_NUMERIC_CHECK);
            }
        }

        return Response::json(['error' => 'User not found.'], 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        if ($id === 'me') {
            $id = Auth::id();
        }

        $isAdmin = Auth::user()->isAdmin();

        if ($id === Auth::id() || $isAdmin) {
            $user = User::find($id);
            if (!is_null($user)) {
                $properties = Input::all();

                if (!$isAdmin) {
                    unset($properties['type']);
                }

                $user->fill($properties);
                $user->save();

                return Response::make($user);
            }
        }

        return Response::json(['error' => 'User not found.'], 404);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        return Response::json(['error' => 'Not implemented.'], 400);
    }


}
