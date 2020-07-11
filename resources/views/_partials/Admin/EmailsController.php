<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Mail\EmailSentByAdmin;
use App\Models\Company;
use App\Models\ContactForm;
use App\Models\EmailSender;
use App\Models\EmailTemplate;
use App\Models\JsonList;
use App\Models\QueuedEmail;
use App\Models\Role;
use App\Models\User;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:admin_view_email_sender');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.emails.emails');
    }

    public function getDT()
    {
        return Datatables::eloquent(EmailSender::query()->orderBy('created_at', 'DESC'))
            ->setRowId(function ($person) {
                return $person->id;
            })
            ->addRowAttr('data-url', function ($person) {
                return route('admin.emails.show', ['id' => $person->id]);
            })
            ->addRowAttr('style', 'cursor: pointer;')
            ->editColumn('status', function ($person) {
                return '<div class="status' . $person->id . '">' . $person->getStatus() . '</div>';
            })
            ->editColumn('sender_user', function ($email) {
                return $email->send_by_user->full_name;
            })
            ->editColumn('content', function ($email) {
                return str_limit(strip_tags($email->content), 25);
            })
            ->editColumn('subject', function ($email) {
                return str_limit(strip_tags($email->subject), 25);
            })
            ->addColumn('action', function ($email) {

                $action = '<div class="btn-group btn-group-justified">
                			<a class="' . config('base.btn.view') . '" href="' . route('admin.emails.show',
                        $email->id) . '" target="_blank">' . trans('global.btn.view') . '</a>';
                if (config('base.Page.delete') == true) {
                    $action .= '<a class="' . config('base.btn.destroy') . '" href="javascript:void(0);" data-href="' . route('admin.emails.destroy',
                            $email->id) . '" onclick="deleteElement(this)">' . trans('global.btn.destroy') . '</a>';
                }

                $action .= '</div>';

                return $action;
            })
            ->make(true);
    }

    /**
     * Get the list of elements for recipients select2
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function getSelRecipients(Request $request)
    {
        $input = $request->input('text');
        if ($input) {
            $roles = Role::whereHas('users', function ($query) use ($input) {
                $query->where('full_name', 'LIKE', "%$input%")->whereNotNull('email');
            })
                ->with([
                    'users' => function ($query) use ($input) {
                        $query->where('full_name', 'LIKE', "%$input%")->whereNotNull('email');
                    }
                ])
                ->get();
        } else {
            $roles = Role::all();
        }

        $result = [];
        foreach ($roles as $role) {
            $user_result = [];
            $user_result[] = ['id' => 'group:role:' . $role->id, 'text' => "All " . str_plural($role->display_name)];
            if ($input) {
                foreach ($role->users as $user) {
                  
                    $user_result[] = ['id' => $user->email, 'text' => $user->full_name];
                }
            }
            $result[] = [
                'id'       => $role->id,
                'text'     => $role->display_name,
                'children' => $user_result,
            ];
        }

        $result[] = [
            'id'       => 'simple_users_with_restaurants',
            'text'     => 'Simple users with restaurants',
            'children' => [['id' => 'group:users:with_restaurants', 'text' => 'All Simple users with restaurants']],
        ];

        $result[] = [
            'id'       => 'restaurants',
            'text'     => 'Restaurants',
            'children' => [
                ['id' => 'group:restaurant:all', 'text' => 'All restaurants'],
                ['id' => 'group:restaurant:with_created_account', 'text' => 'All restaurants with created account'],
                [
                    'id'   => 'group:restaurant:most_popular',
                    'text' => 'All most popular restaurants (not implemented yet, the email will be send to all restaurants)'
                ],
                ['id' => 'group:restaurant:with_dishes', 'text' => 'All restaurants with dishes'],
            ],
        ];

        return json_encode($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admin_email_templates = EmailTemplate::where('admin_notification', 1)->get()->pluck('name',
            'id')->toArray(); // without ->get() I cannot get the translated name
        /**
         * Extra option ca be added: 'contact' => 'Contact (Queue worker required)'
         * Requires code changes on email: EmailSentByAdmin (Is not implemented)
         */
        $email_sender_options = ['custom' => 'Custom', 'noreply' => 'Noreply (Queue worker required)'];

        return view('admin.emails.create',
            ['admin_email_templates' => $admin_email_templates, 'email_sender_options' => $email_sender_options]);
    }

    public function replyToEmail(Request $request)
    {
        if ($request->contact_form_id) {
            $reply_to = ContactForm::find($request->contact_form_id);
            $reply_to->email = $reply_to->from_email;
        } elseif ($request->report_id) {
            $reply_to = Report::find($request->report_id);
            $reply_to->email = $reply_to->user->email;
            $reply_to->subject = trans('admin/report.types.escort', ['username' => $reply_to->model->username]);
            $reply_to->full_name = !empty($reply_to->user->full_name) ? $reply_to->user->full_name : '';
            $reply_to->content = !empty($reply_to->custom_properties) ? implode(', ',
                json_decode($reply_to->custom_properties, true)) : '';
            if ($reply_to->comment) {
                $reply_to->content .= '<br>' . $reply_to->comment;
            }
        }
        $admin_email_templates = EmailTemplate::where('admin_notification', 1)->get()->pluck('name',
            'id')->toArray(); // without ->get() I cannot get the translated name
        $email_sender_options = ['custom' => 'Custom'];

        return view('admin.emails.create', [
            'reply_to'              => $reply_to,
            'admin_email_templates' => $admin_email_templates,
            'email_sender_options'  => $email_sender_options
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->from_email == 'custom') {
            $this->validate($request, [
                'custom_from_email' => 'email|required',
                'custom_to_email'   => 'array|required',
            ]);
        } else {
            $this->validate($request, [
                'from_email' => 'required',
                'to_email'   => 'array|required',
            ]);
        }

        $use_template = isset($request->use_template);
        $content = [
            'form_email'   => $request->from_email,
            'use_template' => $use_template,
        ];

        if ($use_template && $request->from_email != 'custom') {
            $this->validate($request, [
                'admin_email_template' => 'required',
            ]);

            $content['email_template'] = $request->admin_email_template;
        } else {
            $this->validate($request, [
                'subject'       => 'required',
                'email_content' => 'required|min:20',
            ]);

            $content['subject'] = $request->subject;
            $content['content'] = $request->email_content;
        }

        $to_emails = $request->to_email;
        $db_email_list = '';

        $email_sender = new EmailSender();
        $email_sender->sender_user = auth()->user()->id;
        $email_sender->from_email = $request->from_email;
        $email_sender->save();

        if ($to_emails) {

            $recipients = collect();

            /**
             * Trebuie sa filtrez emailurile de grupuri.
             */

            $users_emails = [];

            foreach ($to_emails as $email) {
                $receiver_details = explode(':', $email);

                if (isset($receiver_details[0]) && $receiver_details[0] == 'group') {

                    switch ($receiver_details[1]) {
                        case 'role':
                            $db_email_list .= ' role_id: ' . $receiver_details[2];
                            $role_emails = Role::find($receiver_details[2])
                                ->users(function($q) {
                                    $q->where('status', 1);
                                })
                                ->select('id', 'email', 'locale', 'isocode', DB::raw('full_name AS name'));

                            if ($use_template) {
                                $role_emails->doesntHave('queued_emails', 'and', function ($q) use ($content) {
                                    $q->where('email_template_id', $content['email_template']);
                                });
                            }

                            if (!empty($request->limit)) {
                                $role_emails->limit($request->limit);
                            }

                            $role_emails = $role_emails->get();
                            $recipients = $recipients->merge($role_emails);
                            break;

                        case 'users':
                            $db_email_list .= ' users: ' . $email;
                            if (isset($receiver_details[2]) && $receiver_details[2] == 'with_restaurants') {
                                $users = User::select('id', 'isocode', 'locale', 'email',
                                    DB::raw('full_name AS name'))
                                    ->whereHas('companies')
                                    ->whereNotNull('email');

                                if ($use_template) {
                                    $users->doesntHave('queued_emails', 'and', function ($q) use ($content) {
                                        $q->where('email_template_id', $content['email_template']);
                                    });
                                }

                                if (!empty($request->limit)) {
                                    $users->limit($request->limit);
                                }

                                $users = $users->get();
                                $recipients = $recipients->merge($users);
                            }
                            break;

                        case 'restaurant':
                            $db_email_list .= ' restaurants: ' . $email;
                            $restaurants = Company::select('id', 'country_id', 'email', 'name')
                                ->where('active', 1)
                                ->where(DB::raw('CHAR_LENGTH(email)'), '>', 0)
                                ->where('country_id', '!=', 0)
                                ->whereNotNull('city_id')
                                ->with('country');

                            if ($use_template) {
                                $restaurants->doesntHave('queued_emails', 'and', function ($q) use ($content) {
                                    $q->where('email_template_id', $content['email_template']);
                                });
                            }

                            switch ($receiver_details[2]) {
                                case 'all':
                                    break;
                                case 'with_created_account':
                                    $restaurants = $restaurants->whereHas('users');
                                    break;
                                case 'most_popular':
                                    //Nothing here wight now
                                    break;
                                case 'with_dishes':
                                    $restaurants = $restaurants->orderBy('companies.dishes_count', 'desc')
                                        ->where('companies.dishes_count', '>', 0);
                                    break;
                            }

                            if (!empty($request->limit)) {
                                $restaurants->limit($request->limit);
                            }

                            $restaurants = $restaurants->get();

                            $country_specific = collect(json_decode(JsonList::where('slug',
                                'country_specific')->first()->json_value));

                            foreach ($restaurants as $restaurant) {
                                $restaurant->isocode = isset($restaurant->country->iso_3166_2) ? strtolower($restaurant->country->iso_3166_2) : 'us';
                                $locale = $country_specific->where('isocode', $restaurant->isocode)->first();

                                if ($locale) {
                                    $locale = $locale->language;
                                } else {
                                    $locale = 'en';
                                }

                                $restaurant->locale = $locale;
                                $restaurant->country = null;
                            }

                            $recipients = $recipients->merge($restaurants);
                            break;

                        default:
                            break;
                    }
                } else {
                    $users_emails[] = $receiver_details[0];
                }
            }

            if (count($users_emails) > 0) {
                $users = User::select('id', 'isocode', 'locale', 'email',
                    DB::raw('full_name AS name'))
                    ->whereIn('email', array_values($users_emails))
                    ->whereNotNull('email');
                if ($use_template) {
                    $users->doesntHave('queued_emails', 'and', function ($q) use ($content) {
                        $q->where('email_template_id', $content['email_template']);
                    });
                }

                $users = $users->get();

                $recipients = $recipients->merge($users);
                $db_email_list .= ' ' . implode(', ', array_values($to_emails));
            }


            if ($use_template) {
                DB::transaction(function () use ($recipients, $content, $email_sender) {
                    foreach ($recipients as $recipient) {
                        $recipient_class = get_class($recipient);
                        $recipient->recipient_class = $recipient_class;

                        QueuedEmail::create([
                            'email_id'          => $email_sender->id,
                            'email_template_id' => $content['email_template'],
                            'queued_email_id'   => $recipient->id,
                            'queued_email_type' => $recipient->recipient_class
                        ]);
                    }
                });
            }

            $recipients = $recipients->unique('email');
            $content['custom_email'] = false;
            $content = collect($content);

            foreach ($recipients as $recipient) {
                $recipient->recipient_class = get_class($recipient);
                Mail::to($recipient->email)->queue((new EmailSentByAdmin($recipient, $content)));
            }

            $emails_sent = count($recipients);
        } else {

            /**
             * Custom emails
             */
            if ($request->custom_to_email) {

                $content['custom_email'] = true;
                $content['custom_from_email'] = $request->custom_from_email;
                $content['subject'] = $request->subject;
                $content['content'] = $request->email_content;
                $content['files'] = $request->file('upload_files');
                $recipient = '';
                foreach ($request->custom_to_email as $custom_to_email) {
                    $db_email_list .= $custom_to_email . ', ';
                    Mail::to($custom_to_email)->send(new EmailSentByAdmin($recipient, $content));
                }
                $emails_sent = count($request->custom_to_email);
            }
        }

        $email_sender->to_email = $db_email_list;
        $email_sender->sent = $emails_sent;

        if ($use_template) {
            $email_sender->email_template_id = $request->admin_email_template;
        } else {
            $email_sender->subject = $request->subject;
            $email_sender->content = $request->email_content;
        }

        $email_sender->save();

        return redirect(route('admin.emails.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $email = EmailSender::findorfail($id);

        return view('admin.emails.show')->with(['email' => $email]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (config('base.EmailSender.delete') == false) {
            die();
        }

        $emailSender = EmailSender::find($id);

        if ($emailSender) {
            if (config('base.EmailSender.forceDelete') == true) {
                $emailSender->forceDelete();
            } else {
                $emailSender->delete();
            }

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => trans('admin/emails.error.not_found')]);
    }
}
