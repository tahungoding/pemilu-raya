<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Voter;
use App\Models\Voting;
use Illuminate\Http\Request;
use Alert;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['elections'] = Election::orderByDesc('period')->get();

        return view('admin.election.data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|string|unique:elections,period'
        ]);

        Election::create($request->all())
            ? Alert::success('Sukses', 'Data pemilu berhasil disimpan.')
            : Alert::error('Error', 'Data pemilu gagal disimpan!');

        return redirect(route('elections.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function show(Election $election)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Election $election)
    {
        $request->validate([
            'edit_period' => "required|string|unique:elections,period,$election->id"
        ]);

        $data['period'] = $request->edit_period;

        $election->update($data)
            ? Alert::success('Sukses', 'Data pemilu berhasil diubah.')
            : Alert::error('Error', 'Data pemilu gagal diubah!');

        return redirect(route('elections.index'));
    }

    public function running(Election $election, $runningStatus=1)
    {
        $action = ($runningStatus === 1)
            ? 'Sekarang pemilu dapat menerima voting.'
            : 'Sekarang pemilu tidak dapat menerima voting.';

        if (!$election->archived) {
            $election->running = $runningStatus;

            $election->save()
                ? Alert::info('Sukses', $action)
                : Alert::error('Error', "Pemilu gagal $action");
        } else {
            Alert::info('Info', "Pemilu tidak dapat $action");
        }

        return redirect(route('elections.index'));
    }

    public function resetVoting(Election $election)
    {
        $election->voters()->update(['voted' => 0]) && Voting::destroy($election->votings)
            ? Alert::success('Sukses', 'Hasil voting pemilu berhasil direset.')
            : Alert::error('Error', 'Hasil voting pemilu gagal direset.');

        return redirect(route('elections.index'));
    }

    /**
     * Arsipkan Pemilu (Pemilu selesai).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function archive(Election $election)
    {
        $biggestVote = 0;
        $electedCandidate = null;

        foreach ($election->candidates as $candidate) {
            if (count($candidate->votings) > $biggestVote)
                $electedCandidate = $candidate;
        }

        $data = [
            'total_voters'        => count($election->voters),
            'voted_voters'        => count($election->votedVoters),
            'unvoted_voters'      => count($election->unvotedVoters),
            'total_candidates'    => count($election->candidates),
            'election_winner'     => $electedCandidate->candidate_number,
            'chairman'            => $electedCandidate->chairman_name,
            'vice_chairman'       => $electedCandidate->vice_chairman_name,
            'chairman_photo'      => $electedCandidate->chairman_photo,
            'vice_chairman_photo' => $electedCandidate->vice_chairman_photo,
            'archived'            => 1,
        ];

        $election->votings()->delete();
        $election->voters()->delete();
        $election->candidates()->delete();

        $election->update($data)
            ? Alert::success('Sukses', 'Pemilu berhasil diarsipkan.')
            : Alert::error('Error', 'Pemilu gagal diarsipkan.');

        return redirect(route('elections.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function destroy(Election $election)
    {
        $election->votings()->delete();
        $election->voters()->delete();
        $election->candidates()->delete();

        $election->delete()
            ? Alert::success('Sukses', 'Data pemilu berhasil dihapus.')
            : Alert::error('Error', 'Data pemilu gagal dihapus.');

        return redirect(route('elections.index'));
    }

    /**
     * Remove all resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        $elections = Election::all();

        foreach ($elections as $election) {
            Voting::destroy($election->votings);
            Voter::destroy($election->voters);
            Candidate::destroy($election->candidates);
        }

        Election::destroy($elections)
            ? Alert::success('Sukses', 'Data pemilu berhasil dibersihkan.')
            : Alert::error('Error', 'Data pemilu gagal dibersihkan.');

        return redirect(route('elections.index'));
    }
}
