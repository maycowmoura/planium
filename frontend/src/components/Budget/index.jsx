import './style.css';
import { BsCalendarWeek, BsPatchCheck } from 'react-icons/bs';
import { RiArrowGoBackFill, RiFileExcel2Line, RiCodeBoxLine } from 'react-icons/ri';

export default function Budget({ data, setData, setActive }) {
  const baseurl = process.env.NODE_ENV === 'production'
    ? `/planium/backend/budgets/download/${data.budget.file}/`
    : `http://planium/budgets/download/${data.budget.file}/`;

  function getPlanById(id) {
    return data.plans.filter(plan => plan.codigo === id)[0].nome;
  }

  function numberToCurrency(number) {
    const formatter = new Intl.NumberFormat('pr-BR', {
      style: 'currency',
      currency: 'BRL',
    })
    return formatter.format(number);
  }

  function newBudget() {
    setData(data => ({ ...data, budget: null }));
    setActive('register');
  }


  return (
    <div id="budget" className="card px-4 py-5">
      <h2 className="mb-5 text-center">
        Dados do Orçamento
      </h2>

      {data.budget.people.map((person, i) => (
        <div className="person" key={i}>
          <div>
            <h4 className="text-capitalize">{person.name}</h4>
            <div><BsCalendarWeek /> {person.age} anos de idade</div>
            <div><BsPatchCheck /> {getPlanById(person.planId)}</div>
          </div>
          <div className="price">
            {numberToCurrency(person.price)}
          </div>
        </div>
      ))}

      <div className="text-center mt-5">
        <div className="fs-5 fw-bold">
          Total Geral
        </div>
        <div className="display-5">
          {numberToCurrency(data.budget.total)}
        </div>
      </div>

      <div className="text-center">
        <div class="mt-5 d-flex gap-2 justify-content-center">
          <a href={`${baseurl}/?type=csv`} target="_blank" class="btn btn-secondary">
            <RiFileExcel2Line /> Baixar Proposta em Excel
          </a>
          <a href={`${baseurl}/?type=json`} target="_blank" class="btn btn-secondary">
            <RiCodeBoxLine /> Baixar em JSON
          </a>
        </div>
        <button className="btn btn-info mt-4" onClick={newBudget}>
          <RiArrowGoBackFill /> Novo Orçamento
        </button>

      </div>
    </div>
  );
}