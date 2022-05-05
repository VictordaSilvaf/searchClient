<?php

namespace App\Jobs;

use App\Models\Clientes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SalvarClienteNoBancoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public $lista_clientes
    ) {
        $this->clientes = $lista_clientes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clientes_com_erro = array();
        unset($this->clientes[0]);

        foreach ($this->clientes as $cliente) {
            try {
                if (Clientes::find($cliente['cliente']['id'])) {
                    $listarClientes = Clientes::findOrFail($cliente['cliente']['id'])->first();
                } else {
                    $listarClientes = new Clientes();
                    $listarClientes->id = $cliente['cliente']['id'];
                }

                $listarClientes->codigo = $cliente['cliente']['codigo'];
                $listarClientes->nome = $cliente['cliente']['nome'];
                $listarClientes->fantasia = $cliente['cliente']['fantasia'];
                $listarClientes->tipo = $cliente['cliente']['tipo'];
                $listarClientes->cnpj = $cliente['cliente']['cnpj'];
                $listarClientes->ie_rg = $cliente['cliente']['ie_rg'];
                $listarClientes->endereco = $cliente['cliente']['endereco'];
                $listarClientes->numero = $cliente['cliente']['numero'];
                $listarClientes->bairro = $cliente['cliente']['bairro'];
                $listarClientes->cep = $cliente['cliente']['cep'];
                $listarClientes->cidade = $cliente['cliente']['cidade'];
                $listarClientes->complemento = $cliente['cliente']['complemento'];
                $listarClientes->uf = $cliente['cliente']['uf'];
                $listarClientes->fone = $cliente['cliente']['fone'];
                $listarClientes->email = $cliente['cliente']['email'];
                $listarClientes->situacao = $cliente['cliente']['situacao'];
                $listarClientes->contribuinte = $cliente['cliente']['contribuinte'];
                $listarClientes->site = $cliente['cliente']['site'];
                $listarClientes->celular = $cliente['cliente']['celular'];
                $listarClientes->dataAlteracao = $cliente['cliente']['dataAlteracao'];
                $listarClientes->dataInclusao = $cliente['cliente']['dataInclusao'];
                $listarClientes->sexo = $cliente['cliente']['sexo'];
                $listarClientes->clienteDesde = $cliente['cliente']['clienteDesde'];
                $listarClientes->limiteCredito = intval($cliente['cliente']['limiteCredito']);

                if ($listarClientes->save()) {
                } else {
                    print_r("erro, ");
                }
            } catch (\Throwable $th) {
                array_push($clientes_com_erro, $cliente['cliente']['id']);
            }

            /* Feature: Enviar por email clientes que estão com problema de preenchimento */
        }
    }
}
