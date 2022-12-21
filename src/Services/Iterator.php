<?php

namespace App\Services;

// class ListProductCommandHandler implements CommandHandlerInterface
// {
//     public function __construct(private ProductRepositoryInterface $productRepository, private PriceCalculatorInterface $priceCalculator)
//     {
//     }

//     public function __invoke(ListProductCommand $command)
//     {
//         $result = $this->rentabilityCalculator->calculate($leads);
//     }
// }


final class Iterator
{
    public function __construct(private iterable $calculators)
    {
    }

    public function calculate(Product $leads): RentabilityCalculatorResult
    {
        $rentabilityCalculatorResult = new RentabilityCalculatorResult($product);
        
        foreach ($leads as $lead) {
            $rentabilityCalculatorResult->addLine(
                $this->calculateLeadRentability($lead)
            );
        }
        

        return $rentabilityCalculatorResult;
    }

}