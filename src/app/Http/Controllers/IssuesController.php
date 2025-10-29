<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\IssueCreateRequest;
use App\Http\Requests\IssueUpdateRequest;
use App\Repositories\IssueRepository;
use App\Validators\IssueValidator;
use App\Http\Controllers\Traits\UploadImageTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

/**
 * Class IssuesController.
 *
 * @package namespace App\Http\Controllers;
 */
class IssuesController extends Controller
{
    use UploadImageTrait;

    /**
     * @var IssueRepository
     */
    protected $repository;

    /**
     * @var IssueValidator
     */
    protected $validator;

    /**
     * IssuesController constructor.
     *
     * @param IssueRepository $repository
     * @param IssueValidator $validator
     */
    public function __construct(IssueRepository $repository, IssueValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $perPage = $request->filled('perPage') ? (int) $request->input('perPage') : null;

        $this->repository->pushCriteria(app(\App\Criteria\IssueSearchCriteria::class));

        $issues = $this->repository
            ->skipPresenter() // <<< สำคัญ: กันไม่ให้กลายเป็น array จาก Presenter
            ->scopeQuery(fn($q) => $q->orderBy('created_at', 'asc')->orderBy('id', 'asc'))
            ->paginate($perPage)
            ->withQueryString();

        // กันผลข้างเคียง (เลือกใส่)
        $this->repository->skipPresenter(false);

        return view('issues.index', compact('issues'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  IssueCreateRequest $request
     *
     * @return mixed
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(IssueCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $issue = $this->repository->create($request->all());
            $files = $this->uploadFiles($request);

            foreach ($files as $file) {
                $issue->media()->create([
                    'model' => 'Issue',
                    'model_id' => $issue->id,
                    'image' => $file
                ]);
            }

            $response = [
                'message' => 'บันทึกข้อมูลเสร็จเรียบร้อย',
                'data' => $issue->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function show($id)
    {
        $issue = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $issue,
            ]);
        }

        return view('issues.view', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $issue = $this->repository->find($id);

        return view('issues.edit', compact('issue'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  IssueUpdateRequest $request
     * @param  string            $id
     *
     * @return mixed
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(IssueUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $issue = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Issue updated.',
                'data' => $issue->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error' => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Issue deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Issue deleted.');
    }
    public function reply(Request $request, $id)
    {
        $data = $request->validate(['reply' => 'required|string|max:5000']);

        // อัปเดตได้เมื่อ reply ยังเป็น NULL เท่านั้น (atomic)
        $affected = DB::table('issues')
            ->where('id', $id)
            ->whereNull('reply')
            ->update([
                'reply' => $data['reply'],
                'updated_at' => now(),
            ]);

        if ($affected === 0) {
            return back()->withErrors('ตอบกลับไปแล้ว จึงไม่สามารถแก้ไขได้');
        }

        return back()->with('success', 'บันทึกคำตอบแล้ว');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  IssueCreateRequest $request
     *
     * @return mixed
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */


    public function storeMultiple(Request $request)
    {
        $issues = $request->input('issues');

        // ถ้ามาเป็นสตริง JSON (เช่น multipart ที่ยัด JSON ไว้ใน hidden input)
        if (is_string($issues)) {
            $decoded = json_decode($issues, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $issues = $decoded;
            }
        }

        // ถ้าส่งมาเป็น "อ็อบเจ็กต์เดียว" ให้ห่อเป็นอาเรย์
        if (is_array($issues) && Arr::isAssoc($issues)) {
            $issues = [$issues];
        }

        // ถ้าไม่ได้ส่งคีย์ issues แต่ส่งฟิลด์เดี่ยว ๆ มาก็รองรับ (เพิ่มความยืดหยุ่น)
        if (!is_array($issues) && $request->has('plant_type')) {
            $issues = [
                $request->only([
                    'first_name',
                    'last_name',
                    'agriculturist_code',
                    'station',
                    'plant_type',
                    'disease_insects',
                    'area_percentage',
                    'lat',
                    'long',
                ])
            ];
        }

        if (!is_array($issues)) {
            return response()->json([
                'error' => true,
                'message' => '`issues` ต้องเป็นอาเรย์ของอ็อบเจ็กต์ เช่น [{"plant_type":"..."}, ...]',
            ], 422);
        }

        // validate ให้ตรงสคีมา
        $v = Validator::make(['issues' => $issues], [
            'issues' => 'required|array|min:1',
            'issues.*.first_name' => 'nullable|string|max:255',
            'issues.*.last_name' => 'nullable|string|max:100',
            'issues.*.agriculturist_code' => 'nullable|string|max:100',
            'issues.*.station' => 'nullable|string|max:255',
            'issues.*.plant_type' => 'required|string|max:255',
            'issues.*.disease_insects' => 'nullable|string',
            'issues.*.area_percentage' => 'nullable|numeric|min:0|max:100',
            'issues.*.lat' => 'required|string|max:50',
            'issues.*.long' => 'required|string|max:50',
        ]);
        if ($v->fails()) {
            return response()->json(['error' => true, 'message' => $v->errors()], 422);
        }

        $created = [];
        foreach ($issues as $i => $data) {
            $payload = Arr::only($data, [
                'first_name',
                'last_name',
                'agriculturist_code',
                'station',
                'plant_type',
                'disease_insects',
                'area_percentage',
                'lat',
                'long',
            ]);

            $issue = $this->repository->create($payload);

            // ไฟล์ (ต่อรายการ) — ถ้าไม่มีจะได้ [] ไม่ใช่ null
            $files = $request->file("issues.$i.images", []);
            foreach ($files as $file) {
                $path = $file->store('issues', 'public');
                $issue->media()->create([
                    'model' => 'Issue',
                    'model_id' => $issue->id,
                    'image' => $path,
                ]);
            }

            $created[] = $issue;
        }

        return response()->json([
            'message' => 'บันทึกข้อมูลเสร็จเรียบร้อย',
            'data' => collect($created)->toArray(),
        ], 201);
    }

}
