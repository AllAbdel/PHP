<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TPController extends AbstractController{
    #[Route('/tp_variables')]
    public function tpVariables(): Response
    {
        $nom = 'Allaouat';
        $prenom = 'Abdelslam';
        $age = 20;
        $age2 = 20;
        $classe = 'Parcours DAW2I';
        $beaute = 'Trop beau';
        $Quotient_Intellectuel = 200;

        return $this->render('tp/variables.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => $age,
            'age2' => $age2 * 42,
            'classe' => $classe . " - LP MIAW - IUT D'Evry",
            'Quotient_Intellectuel' => $Quotient_Intellectuel,
            'beaute' => $beaute,
        ]);
    }

    #[Route('/tp_maths')]
    public function tpMaths(): Response
    {
        for ($i = 1; $i <= 10; $i++) {
            dump(4 * $i);
        }
        for ($i = 1; $i <= 10; $i++) {
            dump(2 * $i);
        }
        die();
    }

    #[Route('/tp_if/{condition}', requirements: ['condition' => '\d+'])]
    public function tpIf(int $condition): Response
    {
        if($condition ==1) {
            $result = 'Condition vraie';
        }
        elseif($condition ==2) {
            $result = 'Condition faux';
        }
        else{
            $result = 'Condition inconnue';
        }
        return $this->render('tp/if.html.twig', ['result' => $result]);
    }

    #[Route('/tp_switch/{condition}', requirements: ['condition' => '\d+'])]
    public function tpSwitch(int $condition): Response
    {
        switch ($condition) {
            case 1:
                $result = 'Condition vraie';
                break;
            case 0:
                $result = 'Condition fausse';
                break;
            default:
                $result = 'Condition inconnue';
        }
        return $this->render('tp/switch.html.twig', ['result' => $result]);
    }

    #[Route('/tp_boucle_for')]
    public function tpBoucleFor(): Response
    {
        $additionTable = [];
        $multiplicationTable = [];
        for ($i = 1; $i <= 10; $i++) {
            $additionTable[] = "4 + $i = " . (4 + $i);
            $multiplicationTable[] = "2 x $i = " . (2 * $i);
        }
        return $this->render('tp/boucle_for.html.twig', [
            'additionTable' => $additionTable,
            'multiplicationTable' => $multiplicationTable,
        ]);
    }

    #[Route('/tp_html')]
    public function tpHtml(): Response
    {
        return $this->render('tp/html.html.twig');
    }
}
