<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\BloodSeekerRequest;
    use App\Models\BloodRequest;
    use Exception;
    use Faker\Core\Blood;

    class BloodRequestController extends Controller {
        public function __construct() {
            $this->middleware(['auth:sanctum'], ['only' => ['store']]);
        }

        public function index() {
            try {
                $bloodRequests = BloodRequest::with(['user.profile', 'district', 'area'])
                    ->where('status', 'active')
                    ->where('accepted_date', '>=', today())
                    ->latest()
                    ->paginate(12);

                return response([
                    'status' => 'success',
                    'statusCode' => 200,
                    'data' => $bloodRequests
                ], 200);
            } catch (Exception $e) {
                return serverError($e);
            }
        }

        public function store(BloodSeekerRequest $request) {
            try {
                $data = [
                    'user_id' => auth()->id(),
                    'district_id' => $request->district_id,
                    'area_id' => $request->area_id,
                    'hospital' => $request->hospital,
                    'blood_group' => $request->blood_group,
                    'emergency' => $request->emergency,
                    'accepted_date' => $request->accepted_date,
                    'gender' => $request->gender,
                    'religion' => $request->religion,
                    'description' => $request->description,
                    'status' => $request->status ?? 'active',
                ];

                if ($request->has('emergency') && $request->emergency === true) {
                    $data['accepted_date'] = today();
                }

                $bloodRequest = BloodRequest::create($data);

                return response([
                                    'status' => 'success',
                                    'statusCode' => 201,
                                    'message' => 'Your request for blood has been posted',
                                    'data' => $bloodRequest->load(['user.profile', 'district', 'area'])
                                ], 201);
            } catch (Exception $e) {
                return serverError($e);
            }
        }

        public function show($id) {
            try {
                $bloodRequest = BloodRequest::with(['user.profile', 'district', 'area'])
                    ->where('id', $id)
                    ->where('status', 'active')
                    ->first();

                if ($bloodRequest) {
                    return response([
                                        'status' => 'success',
                                        'statusCode' => 200,
                                        'data' => $bloodRequest
                                    ], 200);
                }
                else {
                    return itemNotFound();
                }
            } catch (Exception $e) {
                return serverError($e);
            }
        }

        public function filterSeekerRequest() {
            try {
                $bloodRequest = BloodRequest::query();

                if (request()->has('district_id') && request('district_id')) {
                    $bloodRequest = $bloodRequest->where('district_id', request('district_id'));
                }

                if (request()->has('area_id') && request('area_id')) {
                    $bloodRequest = $bloodRequest->where('area_id', request('area_id'));
                }

                if (request()->has('blood_group') && request('blood_group')) {
                    $bloodRequest = $bloodRequest->where('blood_group', request('blood_group'));
                }

                if (request()->has('gender') && request('gender')) {
                    $bloodRequest = $bloodRequest->where('gender', request('gender'));
                }

                if (request()->has('seeker_type') && request('seeker_type')) {
                    $bloodRequest = $bloodRequest->where('emergency', request('seeker_type'));
                }

                if (request()->has('religion') && request('religion')) {
                    $bloodRequest = $bloodRequest->where('religion', request('religion'));
                }

                $bloodRequest = $bloodRequest->with(['user.profile','district', 'area'])->where('status', 'active')
                    ->where('accepted_date', '>=', today())
                    ->latest()
                    ->paginate(12);


                return response([
                    'status' => 'success',
                    'statusCode' => 200,
                    'data' => $bloodRequest
                ], 200);

            } catch (Exception $e) {
                return serverError($e);
            }
        }


        public function authUserBloodRequests()
        {
            try{
                $bloodRequests = BloodRequest::where('user_id', auth()->id())->latest()->paginate();

                return response([
                    'status' => 'success',
                    'statusCode' => 200,
                    'data' => $bloodRequests
                ], 200);
            }catch (Exception $e){
                return serverError($e);
            }
        }
    }
