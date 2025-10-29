<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\DiseaseInsectCreateRequest;
use App\Http\Requests\DiseaseInsectUpdateRequest;
use App\Repositories\DiseaseInsectRepository;
use App\Validators\DiseaseInsectValidator;
use App\Http\Controllers\Traits\UploadImageTrait;

/**
 * จัดการข้อมูลโรคพืชและแมลง (CRUD) รวมถึงอัปโหลด/ลบรูปประกอบ
 */
class DiseaseInsectsController extends Controller
{
    use UploadImageTrait;

    /** @var DiseaseInsectRepository */
    protected $repository;

    /** @var DiseaseInsectValidator */
    protected $validator;

    /**
     * สร้างคอนโทรลเลอร์
     *
     * @param  DiseaseInsectRepository $repository
     * @param  DiseaseInsectValidator  $validator
     */
    public function __construct(DiseaseInsectRepository $repository, DiseaseInsectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * แสดงรายการโรคพืชและแมลง (มีค้นหา/แบ่งหน้า)
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->repository->pushCriteria(app('App\Criteria\DiseaseInsectSearchCriteria'));

        $diseaseInsects = $this->repository
            ->select(['id', 'name', 'type', 'updated_at']) // เฉพาะคอลัมน์ที่แสดงจริง
            ->orderBy('updated_at', 'asc')
            ->Paginate(20);                       // เบากว่า paginate()

        if (request()->wantsJson()) {
            return response()->json($diseaseInsects);
        }
        return view('disease_insects.index', compact('diseaseInsects'));
    }

    /**
     * แสดงฟอร์มเพิ่มข้อมูล
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function add()
    {
        $diseaseInsect = null;
        return view('disease_insects.add', compact('diseaseInsect'));
    }

    /**
     * บันทึกรายการใหม่
     *
     * @param  DiseaseInsectCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */


    public function store(DiseaseInsectCreateRequest $request)
    {
        try {
            // ถ้าใช้ FormRequest แล้ว จะตัดบรรทัด Prettus validator นี้ออกก็ได้
            // $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            DB::beginTransaction();

            $diseaseInsect = $this->repository->create($request->all());

            foreach (($this->uploadFiles($request, 'uploads/disease_insects') ?? []) as $file) {
                $diseaseInsect->media()->create([
                    'model' => 'DiseaseInsect',
                    'model_id' => $diseaseInsect->id,
                    'image' => $file,
                ]);
            }

            DB::commit();

            $response = ['message' => 'บันทึกข้อมูลเสร็จเรียบร้อย', 'data' => $diseaseInsect->toArray()];
            return $request->wantsJson()
                ? response()->json($response)
                : redirect()->route('disease_insects.index')->with('success', $response['message']);

        } catch (ValidatorException $e) {
            DB::rollBack();
            return $request->wantsJson()
                ? response()->json(['error' => true, 'message' => $e->getMessageBag()])
                : redirect()->back()->withErrors($e->getMessageBag())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $request->wantsJson()
                ? response()->json(['error' => true, 'message' => $e->getMessage()], 422)
                : redirect()->back()->withErrors($e->getMessage())->withInput();
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
        $diseaseInsect = $this->repository->find($id);

        if (request()->wantsJson()) {
            return response()->json(['data' => $diseaseInsect]);
        }

        return view('diseaseInsects.show', compact('diseaseInsect'));
    }

    /**
     * แสดงฟอร์มแก้ไข
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $diseaseInsect = $this->repository
            ->select([
                'id',
                'name',
                'type',
                'cause',
                'symptom',
                'life_cycle',
                'effect',
                'protect_eliminate',
                'plant_type'
            ])
            ->find($id);

        $diseaseInsect->load([
            'media' => function ($q) {
                $q->select(['id', 'model_id', 'image']);
            }
        ]);

        return view('disease_insects.add', compact('diseaseInsect'));
    }

    /**
     * อัปเดตรายการที่มีอยู่
     *
     * @param  DiseaseInsectUpdateRequest $request
     * @param  string                     $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function update(DiseaseInsectUpdateRequest $request, $id)
    {
        try {
            // $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            DB::beginTransaction();

            $diseaseInsect = $this->repository->update($request->all(), $id);

            if ($request->filled('delete_image')) {
                $ids = collect($request->input('delete_image'))
                    ->map(fn($v) => (int) $v)->filter()->unique()->values();

                $items = $diseaseInsect->media()->whereIn('id', $ids)->get(['id', 'image']);

                $paths = $items->pluck('image')->map(
                    fn($p) => ltrim(preg_replace('#^(public/|storage/)#', '', $p), '/')
                )->all();

                if (!empty($paths)) {
                    Storage::disk('public')->delete($paths); // ลบทีเดียว
                }
                $diseaseInsect->media()->whereIn('id', $ids)->delete();
            }

            foreach (($this->uploadFiles($request, 'uploads/disease_insects') ?? []) as $file) {
                $diseaseInsect->media()->create([
                    'model' => 'DiseaseInsect',
                    'model_id' => $diseaseInsect->id,
                    'image' => $file,
                ]);
            }

            DB::commit();

            $response = ['message' => 'อัพเดทข้อมูลเสร็จเรียบร้อย', 'data' => $diseaseInsect->toArray()];
            return request()->wantsJson()
                ? response()->json($response)
                : redirect()->route('disease_insects.index')->with('success', $response['message']);

        } catch (ValidatorException $e) {
            DB::rollBack();
            return request()->wantsJson()
                ? response()->json(['error' => true, 'message' => $e->getMessageBag()])
                : redirect()->back()->withErrors($e->getMessageBag())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            return request()->wantsJson()
                ? response()->json(['error' => true, 'message' => $e->getMessage()], 422)
                : redirect()->back()->withErrors($e->getMessage())->withInput();
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
    $d = $this->repository->find($id);

    if ($d && $d->media && $d->media->isNotEmpty()) {
        $paths = $d->media->pluck('image')->map(
            fn($p) => ltrim(preg_replace('#^(public/|storage/)#', '', $p), '/')
        )->all();

        if (!empty($paths)) {
            Storage::disk('public')->delete($paths); // ลบหลายไฟล์ครั้งเดียว
        }
        $d->media()->delete();
    }

    $deleted = $this->repository->delete($id);

    if (request()->wantsJson()) {
        return response()->json(['message' => 'DiseaseInsect deleted.', 'deleted' => $deleted]);
    }
    return redirect()->back()->with('success', 'ลบข้อมูลเสร็จเรียบร้อย');
}

}
