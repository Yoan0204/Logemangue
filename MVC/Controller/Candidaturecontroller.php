<?php

class Candidaturecontroller {
    private $Candidaturemodel;

    public function __construct($Candidaturemodel) {
        $this->Candidaturemodel = $Candidaturemodel;
    }

    public function showCandidatures() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $candidatures = $this->candidatureModel->getCandidaturesByUserId($userId);
        $user = $this->userModel->getUserById($userId);

        $isEtudiant = $user['role'] === 'etudiant';
        $isAdmin = $user['role'] === 'admin';

        require_once 'MVC/View/Candidatureview.php';
    }

 </td>
                            <td><?php echo htmlspecialchars($candidature['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($candidature['date_fin']); ?></td>
                            <td>
                                <?php if ($candidature['statut'] === 'Refusée'): ?>
                                    <span class="status-badge red"><?php echo htmlspecialchars($candidature['statut']); ?></span>
                                <?php elseif ($candidature['statut'] === 'Approuvée'): ?>
                                    <span class="status-badge green"><?php echo htmlspecialchars($candidature['statut']); ?></span>
                                <?php else: ?>
                                    <span class="status-badge"><?php echo htmlspecialchars($candidature['statut']); ?></span>
                                <?php endif; ?>
                                
                            </td>
                            <td class="fw-bold">
                                <?php echo htmlspecialchars($candidature['montant']); ?> €
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

     <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish">Publier une annonce</a>
      <?php endif; ?>
      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>        
      <?php endif; ?>
      <?php if ($isEtudiant): ?>
      <a class="nav-link active-link" href="candidatures">Mes candidatures</a>        
      <?php endif; ?>
      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
        <?php if ($isAdmin): ?>
      <a class="nav-link" href="admin">Admin ⚙️</a>
        <?php endif; ?>

    }