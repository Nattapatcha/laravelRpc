<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PesticideCreateRequest;
use App\Http\Requests\PesticideUpdateRequest;
use App\Repositories\PesticideRepository;
use App\Repositories\GroupRepository;
use App\Validators\PesticideValidator;
use App\Http\Controllers\Traits\UploadImageTrait;
use Illuminate\Support\Facades\Storage;

/**
 * จัดการข้อมูลสารกำจัดศัตรูพืช (CRUD) และอัปโหลด/ลบรูปประกอบ
 */
class PesticidesController extends Controller
{
    use UploadImageTrait;

    /**
     * @var PesticideRepository
     */
    protected $repository;

    /**
     * @var PesticideValidator
     */
    protected $validator;

    /**
     * @var GroupRepository
     */
    protected $groupRepo;

    /**
     * สร้างคอนโทรลเลอร์
     *
     * @param  PesticideRepository $repository
     * @param  PesticideValidator  $validator
     * @param  GroupRepository     $groupRepo
     */
    public function __construct(
        PesticideRepository $repository,
        PesticideValidator $validator,
        GroupRepository $groupRepo
    ) {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->groupRepo  = $groupRepo;
    }

    /**
     * แสดงรายการสารกำจัดศัตรูพืช (มีค้นหา/แบ่งหน้า)
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
{
    $this->repository->pushCriteria(app('App\Criteria\PesticideSearchCriteria'));

    $pesticides = $this->repository
    ->select(['id','name','trademark_name','group_id','plant','updated_at'])
    ->with(['group' => fn($q): mixed => $q->select(['id','name'])])
    ->orderBy('updated_at','asc')
    ->SimplePaginate(20);

    if (request()->wantsJson()) return response()->json($pesticides);
    return view('pesticides.index', compact('pesticides'));
}


    /**
     * แสดงฟอร์มเพิ่มข้อมูล
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function add()
    {
        $pesticide = null;
        $groups = $this->groupRepo->getList();
        return view('pesticides.add', compact('pesticide', 'groups'));
    }

    /**
     * บันทึกข้อมูลรายการใหม่
     *
     * @param  PesticideCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function store(PesticideCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $pesticide = $this->repository->create($request->all());

            // อัปโหลดรูปใหม่ → เก็บที่ uploads/pesticides (บน public disk)
            foreach ($this->uploadFiles($request, 'uploads/pesticides') as $file) {
                $pesticide->media()->create([
                    'model'    => 'Pesticide',
                    'model_id' => $pesticide->id,
                    'image'    => $file, // เช่น 'uploads/pesticides/xxxx.png'
                ]);
            }

            $response = [
                'message' => 'บันทึกข้อมูลเสร็จเรียบร้อย',
                'data'    => $pesticide->toArray(),
            ];

            if ($request->wantsJson()) {
                return response()->json($response);
            }

            return redirect()->route('pesticides.index')->with('success', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => true, 'message' => $e->getMessageBag()]);
            }
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * แสดงรายละเอียดรายการเดียว
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $pesticide = $this->repository->find($id);

        if (request()->wantsJson()) {
            return response()->json(['data' => $pesticide]);
        }

        return view('pesticides.show', compact('pesticide'));
    }

    /**
     * แสดงฟอร์มแก้ไข
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $pesticide = $this->repository->find($id);
        $pesticide->load('media'); 
        $groups = $this->groupRepo->getList();

        return view('pesticides.add', compact('pesticide', 'groups'));
    }

    /**
     * อัปเดตรายการที่มีอยู่
     *
     * @param  PesticideUpdateRequest $request
     * @param  string                 $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function update(PesticideUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            // อัปเดตข้อมูลหลักก่อน
            $pesticide = $this->repository->update($request->all(), $id);

            // ลบรูปที่ผู้ใช้ติ๊ก (ลบไฟล์จริง + เรคคอร์ด)
            if ($request->filled('delete_image')) {
                $ids = collect($request->input('delete_image'))
                    ->map(fn($v) => (int) $v)
                    ->filter()
                    ->unique()
                    ->values();

                $items = $pesticide->media()->whereIn('id', $ids)->get();

                foreach ($items as $m) {
                    $path = ltrim(preg_replace('#^(public/|storage/)#', '', $m->image), '/');
                    Storage::disk('public')->delete($path); // ไม่มีไฟล์ก็เงียบ ๆ
                }

                $pesticide->media()->whereIn('id', $ids)->delete();
            }

            // อัปโหลดรูปใหม่ (ถ้ามี)
            foreach ($this->uploadFiles($request, 'uploads/pesticides') as $file) {
                $pesticide->media()->create([
                    'model'    => 'Pesticide',
                    'model_id' => $pesticide->id,
                    'image'    => $file, // เช่น 'uploads/pesticides/xxxx.png'
                ]);
            }

            $response = [
                'message' => 'อัพเดทข้อมูลเสร็จเรียบร้อย',
                'data'    => $pesticide->toArray(),
            ];

            if ($request->wantsJson()) {
                return response()->json($response);
            }

            return redirect()->route('pesticides.index')->with('success', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => true, 'message' => $e->getMessageBag()]);
            }
            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * ลบรายการ พร้อมเคลียร์ไฟล์รูปทั้งหมดที่เกี่ยวข้อง
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // ลบไฟล์รูปทั้งหมดของรายการนี้ (ถ้ามี)
        $pesticide = $this->repository->find($id);
        if ($pesticide && $pesticide->media) {
            foreach ($pesticide->media as $m) {
                $path = ltrim(preg_replace('#^(public/|storage/)#', '', $m->image), '/');
                Storage::disk('public')->delete($path);
                $m->delete();
            }
        }

        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Pesticide deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('success', 'ลบข้อมูลเสร็จเรียบร้อย');
    }
}
