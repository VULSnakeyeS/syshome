namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configurazione;

class GestioneAppController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $data_inicio = $request->input('data_inicio');
        $data_fine = $request->input('data_fine');

        $query = Configurazione::query();

        if ($search) {
            $query->where('nome', 'like', "%{$search}%");
        }

        if ($data_inicio) {
            $query->whereDate('created_at', '>=', $data_inicio);
        }

        if ($data_fine) {
            $query->whereDate('created_at', '<=', $data_fine);
        }

        $configurazioni = $query->paginate(10);

        return view('gestioneapp.index', compact('configurazioni'));
    }

    public function create()
    {
        return view('gestioneapp.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valore' => 'required|string|max:255',
        ]);

        Configurazione::create($request->all());

        return redirect()->route('gestioneapp.index')->with('success', 'Configurazione creata con successo.');
    }

    public function edit(Configurazione $configurazione)
    {
        return view('gestioneapp.edit', compact('configurazione'));
    }

    public function update(Request $request, Configurazione $configurazione)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valore' => 'required|string|max:255',
        ]);

        $configurazione->update($request->all());

        return redirect()->route('gestioneapp.index')->with('success', 'Configurazione aggiornata con successo.');
    }

    public function destroy(Configurazione $configurazione)
    {
        $configurazione->delete();

        return redirect()->route('gestioneapp.index')->with('success', 'Configurazione eliminata con successo.');
    }
}